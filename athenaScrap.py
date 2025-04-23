import json
import os
import sys
import requests
from bs4 import BeautifulSoup
import time
import mysql.connector
from mysql.connector import Error


def scrape_libgen(search_term):
    all_results = []
    page_number = 1
    # Đặt total_pages là một số rất lớn để vòng lặp tiếp tục cho đến khi không tìm thấy link trang tiếp theo
    total_pages = 9999  
    last_next_link = None

    print(f"Starting search for: {search_term}")

    while page_number <= total_pages:
        print(f"Processing page {page_number}...")
        page_results = scrape_libgen_page(search_term, page_number)
        if not page_results:
            print(f"No results on page {page_number}")
            break

        print(f"Trang {page_number}: Tìm được {len(page_results)} cuốn sách có download link")
        all_results.extend(page_results)

        # Lấy link của trang hiện tại để xác định trang tiếp theo
        try:
            base_url = "https://libgen.is/search.php"
            params = {
                "req": search_term,
                "res": 100,
                "view": "detailed",
                "phrase": 1,
                "column": "def",
                "page": page_number
            }
            response = requests.get(base_url, params=params, timeout=30)
            response.raise_for_status()
            soup = BeautifulSoup(response.content, "html.parser")
            
            # Trên trang đầu tiên, dùng selector "td:nth-child(2) font a" để lấy link đến trang 2
            if page_number == 1:
                next_page_elem = soup.select_one("td:nth-child(2) font a")
            else:
                # Từ trang thứ 2 đến trước trang cuối, dùng selector "font+ font a"
                next_page_elem = soup.select_one("font+ font a")
            
            if next_page_elem:
                next_page_link = next_page_elem.get("href")
            else:
                next_page_link = None

            # Nếu không tìm thấy link trang tiếp theo, dừng vòng lặp
            if not next_page_link:
                print("No next page link found, stopping.")
                break

            # So sánh link trang tiếp theo với link của trang trước đó:
            if last_next_link and next_page_link == last_next_link:
                print("Next page link is same as previous, reached last page, stopping.")
                break

            # Lưu link trang tiếp theo làm tham chiếu cho lần lặp sau
            last_next_link = next_page_link

            # Lấy số trang tiếp theo từ tham số "page" trong URL
            from urllib.parse import urlparse, parse_qs
            parsed = urlparse(next_page_link)
            qs = parse_qs(parsed.query)
            if "page" in qs:
                page_number = int(qs["page"][0])
            else:
                # Nếu không có tham số, tăng dần
                page_number += 1

        except Exception as e:
            print(f"Error detecting next page link on page {page_number}: {e}")
            break

        time.sleep(1)
    
    print(f"\nSearch completed. Total books extracted: {len(all_results)}")
    return all_results


def scrape_libgen_page(search_term, page_number=1):
    base_url = "https://libgen.is/search.php"
    params = {
        "req": search_term,
        "res": 100,
        "view": "detailed",
        "phrase": 1,
        "column": "def",
        "page": page_number
    }
    
    try:
        response = requests.get(base_url, params=params, timeout=30)
        response.raise_for_status()
    except Exception as e:
        print(f"Error accessing page {page_number}: {e}")
        return None

    soup = BeautifulSoup(response.content, "html.parser")
    
    # Kiểm tra thông báo không tìm thấy sách
    no_results = soup.select("body > c > table > tr > td > font")
    if no_results and "No such book" in no_results[0].get_text():
        return None
    
    # Lấy danh sách các bảng chứa kết quả
    book_tables = soup.select("table.c")
    # Nếu chỉ có 1 bảng, thử lấy các bảng con bên trong nó
    if len(book_tables) == 1:
        nested_tables = book_tables[0].select("table")
        if nested_tables:
            book_tables = nested_tables

    if not book_tables:
        print(f"No books found on page {page_number}")
        return None

    books = []
    for book_table in book_tables:
        try:
            book = extract_book_data(book_table)
            if book and book.get("download") and book.get("download").strip() != "":
                books.append(book)
        except Exception as e:
            print(f"Error processing a book on page {page_number}: {e}")
            continue
    
    return books

def extract_book_data(book_table):
    title_elems = book_table.select("td:nth-child(3) b a")
    title = title_elems[0].get_text(strip=True) if title_elems else ""
    
    author_elems = book_table.select("tr:nth-child(3) td+ td")
    author = author_elems[0].get_text(strip=True) if author_elems else ""
    
    link_elems = book_table.select("td:nth-child(3) b a")
    link = link_elems[0].get("href") if link_elems else ""
    if link.startswith(".."):
        link = "https://libgen.is" + link[2:]
    
    deeplinks = ""
    download = ""
    cover = ""  # khởi tạo biến cover
    if link:
        try:
            book_page_response = requests.get(link)
            book_page_response.raise_for_status()
            book_soup = BeautifulSoup(book_page_response.content, "html.parser")

            # Scrap link ảnh bìa từ trang chi tiết
            cover_img_elem = book_soup.select_one("img")
            cover = cover_img_elem.get("src") if cover_img_elem else ""
            if cover and not cover.startswith("http"):
                cover = "https://libgen.is/" + cover.lstrip("/")

            dl_link_elems = book_soup.select("td td:nth-child(2) a")
            dl_link = dl_link_elems[0].get("href") if dl_link_elems else ""
            
            if dl_link:
                if dl_link.startswith(".."):
                    deeplinks = "https://libgen.li" + dl_link[2:]
                else:
                    deeplinks = dl_link
                
                if deeplinks:
                    sub_page_response = requests.get(deeplinks)
                    sub_page_response.raise_for_status()
                    sub_soup = BeautifulSoup(sub_page_response.content, "html.parser")
                    h2_elem = sub_soup.find("h2")
                    if h2_elem and h2_elem.parent and h2_elem.parent.name == "a":
                        h2_link = h2_elem.parent.get("href")
                        if h2_link:
                            download = "http://libgen.li/" + h2_link
        except Exception as e:
            print(f"Error processing links for: {title}, error: {e}")
    
    id_elems = book_table.select("tr:nth-child(8) td:nth-child(4)")
    book_id = id_elems[0].get_text(strip=True) if id_elems else ""
    
    isbn_elems = book_table.select("tr:nth-child(8) td:nth-child(2)")
    isbn = isbn_elems[0].get_text(strip=True) if isbn_elems else ""

    extension_elems = book_table.select("tr:nth-child(10) td:nth-child(4)")
    extension = extension_elems[0].get_text(strip=True) if extension_elems else ""
    
    return {
        "title": title,
        "author": author,
        "link": link,
        "deeplinks": deeplinks,
        "download": download,
        "ID": book_id,
        "ISBN": isbn, 
        "cover": cover,
        "extension": extension
    }

def get_unique_filepath(folder, filename):
    base, ext = os.path.splitext(filename)
    unique_filename = filename
    counter = 1
    while os.path.exists(os.path.join(folder, unique_filename)):
        unique_filename = f"{base}_{counter}{ext}"
        counter += 1
    return os.path.join(folder, unique_filename)

def download_and_upload_book(book, upload_folder="/home/group5-sp25/public_html/uploads/", max_retries=3):
    if book.get("extension", "").lower() != "pdf":
        print(f"Skipping book '{book.get('title')}' because extension is not pdf")
        return None


    if not book.get("download"):
        print(f"No download link for {book.get('title')}")
        return None

    if not os.path.exists(upload_folder):
        os.makedirs(upload_folder)

    title = book["title"]
    invalid_chars = ['<', '>', ':', '"', '/', '\\', '|', '?', '*', '=', '&']
    for char in invalid_chars:
        title = title.replace(char, '_')
    
    unique_id = book.get("ID") or book.get("ISBN") or ""
    raw_filename = f"{title[:100]}_{unique_id}.pdf"
    filepath = get_unique_filepath(upload_folder, raw_filename)
    
    print(f"Processing download for: {book['title']}, saving as: {os.path.basename(filepath)}")
    
    retry_count = 0
    while retry_count < max_retries:
        try:
            print(f"Downloading {os.path.basename(filepath)} (attempt {retry_count + 1}/{max_retries})...")
            time.sleep(2 * retry_count + 1)
            response = requests.get(
                book["download"],
                headers={
                    'User-Agent': 'Mozilla/5.0',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language': 'en-US,en;q=0.5',
                    'Connection': 'keep-alive'
                },
                stream=True,
                timeout=30
            )
            response.raise_for_status()
            
            content_type = response.headers.get('content-type', '').lower()
            if 'pdf' not in content_type:
                print(f"Warning: Unexpected content type {content_type} for {os.path.basename(filepath)}. Skipping download.")

            with open(filepath, 'wb') as f:
                for chunk in response.iter_content(chunk_size=8192):
                    if chunk:
                        f.write(chunk)
            
            print(f"Successfully downloaded and uploaded: {os.path.basename(filepath)}")
            return filepath
            
        except requests.exceptions.RequestException as e:
            print(f"Attempt {retry_count + 1} failed for {os.path.basename(filepath)}: {str(e)}")
            retry_count += 1
            if retry_count >= max_retries:
                print(f"Failed to download {os.path.basename(filepath)} after {max_retries} attempts")
                return None
            continue
        except Exception as e:
            print(f"Unexpected error downloading {os.path.basename(filepath)}: {str(e)}")
            return None

    return None

def download_and_upload_image(book, images_folder="/home/group5-sp25/public_html/images/"):
    if not book.get("cover"):
        print(f"No cover image link for {book.get('title')}")
        return None
    
    # Tạo thư mục nếu chưa tồn tại
    os.makedirs(images_folder, exist_ok=True)
    
    title = book["title"]
    invalid_chars = ['<', '>', ':', '"', '/', '\\', '|', '?', '*', '=', '&']
    for char in invalid_chars:
        title = title.replace(char, '_')
    
    cover_link = book["cover"]
    # Lấy phần mở rộng từ link, nếu không có hoặc không hợp lệ thì mặc định .jpg
    ext = os.path.splitext(cover_link)[1].lower()
    if not ext or ext not in ['.jpg', '.jpeg', '.png', '.gif']:
        ext = ".jpg"
    
    unique_filename = f"{title[:100]}{ext}"
    filepath = get_unique_filepath(images_folder, unique_filename)
    
    print(f"Downloading cover image for: {book['title']}, saving as: {os.path.basename(filepath)}")
    
    try:
        response = requests.get(cover_link, stream=True, timeout=30)
        response.raise_for_status()  # Kiểm tra lỗi HTTP
        
        # Ghi dữ liệu ảnh vào file theo từng chunk nhỏ
        with open(filepath, 'wb') as f:
            for chunk in response.iter_content(chunk_size=1024):
                if chunk:
                    f.write(chunk)
        
        print(f"✅ Ảnh đã được tải thành công và lưu tại: {filepath}")
        return filepath
    except requests.exceptions.RequestException as e:
        print(f"❌ Lỗi khi tải ảnh cho {book['title']}: {e}")
        return None
    except Exception as e:
        print(f"❌ Lỗi khác khi tải ảnh cho {book['title']}: {e}")
        return None


def create_db_connection():
    try:
        connection = mysql.connector.connect(
            host="localhost",
            user="group5-sp25",
            password="olE_miss2025",
            database="group5-sp25"
        )
        return connection
    except Error as e:
        print(f"Error connecting to MySQL database: {e}")
        return None
    
def book_exists(book_link):
    connection = create_db_connection()
    if connection:
        try:
            cursor = connection.cursor()
            query = "SELECT COUNT(*) FROM Books WHERE Link = %s"
            cursor.execute(query, (book_link,))
            result = cursor.fetchone()
            return result[0] > 0
        except Error as e:
            print(f"Error checking book existence: {e}")
            return False
        finally:
            cursor.close()
            connection.close()
    return False

def store_book_info(book, file_path):
    try:
        connection = create_db_connection()
        if connection:
            cursor = connection.cursor()
            
            # Prepare SQL query với cột Cover để lưu file path của ảnh bìa
            sql = """INSERT IGNORE INTO Books 
                    (Title, Author, Link, `FilePath`, BookID, ISBN, DownloadLink, Cover, AuthorID) 
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"""
            
            val = (
                book.get("title", ""),
                book.get("author", ""),
                book.get("link", ""),
                file_path,
                book.get("ID", ""),
                book.get("ISBN", ""),
                book.get("download", ""),
                book.get("cover", ""), 
                None
            )
            cursor.execute(sql, val)
            connection.commit()
            print(f"Successfully stored book info in database: {book.get('title', '')}")
            return True
    except Error as e:
        print(f"Error storing book info in database: {e}")
        return False
    finally:
        if connection and connection.is_connected():
            cursor.close()
            connection.close()

def main():
    if len(sys.argv) > 1:
        # Chuyển từ khóa: nếu nhận "Think+And+Grow+Rich" chuyển thành "Think And Grow Rich"
        search_term = " ".join(sys.argv[1:]).replace("+", " ")
    else:
        print("No search term provided. Exiting.")
        sys.exit(1)
    
    print(f"Searching for: {search_term}")
    books = scrape_libgen(search_term)

    if not books:
        print("No books found with download links.")
        sys.exit(0)

    print(f"\nFound {len(books)} books with non-empty download links.")
    
    upload_folder = "/home/group5-sp25/public_html/uploads/"
    images_folder = "/home/group5-sp25/public_html/images/"
    results = []
    for book in books:
        if book_exists(book.get("link", "")):
            print(f"Book '{book.get('title')}' already exists in the database. Skipping download and upload.")
            continue
        file_path = download_and_upload_book(book, upload_folder)
        if file_path:
            # Tải và upload ảnh bìa nếu link ảnh tồn tại
            cover_path = download_and_upload_image(book, images_folder)
            if cover_path:
                book["cover"] = cover_path
            store_book_info(book, file_path)
            
            results.append({
                "title": book["title"],
                "author": book["author"],
                "link": book["link"],
                "filepath": file_path,
                "ID": book["ID"],
                "ISBN": book["ISBN"],
                "cover": book.get("cover", "")
            })
        time.sleep(1)
    
    print(json.dumps(results))

if __name__ == '__main__':
    main()
