import requests
import urllib
import random
from fake_useragent import UserAgent
from bs4 import BeautifulSoup
from urllib.request import urlopen
from urllib import error
import time
import google
from googlesearch import search
import nltk
# nltk.download('all')
# nltk.download('punkt')
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from collections import OrderedDict
# _____________________________________DB_connection____________________________________
import mysql.connector
from mysql.connector import Error
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='plagiarism_detection',
                                         user='root',
                                         password='')
    cursor = connection.cursor()
except mysql.connector.Error as error:
    print(error)
# _____________________________________title_URL________________________________________


def google_scrape(url):
    thepage = urlopen(url)
    soup = BeautifulSoup(thepage, "html.parser")
    return soup.title.text
# ___________________________________Similarity_function__________________________________


def functionS(X: str, Y: str):
    # tokenization
    X_list = word_tokenize(X)
    Y_list = word_tokenize(Y)
    # sw contains the list of stopwords
    sw = stopwords.words('english', 'french')
    # sw=stopwords.words('french')
    #print (sw)
    l1 = []
    l2 = []
    # remove stop words from string
    X_set = {w for w in X_list if not w in sw}
    Y_set = {w for w in Y_list if not w in sw}
    # form a set containing keywords of both strings
    rvector = X_set.union(Y_set)
    for w in rvector:
        if w in X_set:
            l1.append(1)  # create a vector
        else:
            l1.append(0)
        if w in Y_set:
            l2.append(1)
        else:
            l2.append(0)
    c = 0
    # cosine formula
    for i in range(len(rvector)):
        c += l1[i]*l2[i]
    try:
        # print((sum(l1)*sum(l2))**0.5)
        # print(c)
        cosine = (c / float((sum(l1)*sum(l2))**0.5))
        #print("similarity: ", cosine)
    except ZeroDivisionError:
        print("devision By 0")
    cosine = cosine*100
    cosine = round(cosine, 2)
    return cosine
# ___________________________________Convert_URL_to_Text___________________________________


def convText(url: str):
    #html = requests.get(url).content
    html = urlopen(url).read()
    soup = BeautifulSoup(html, "lxml")
    # kill all script and style elements
    for script in soup(["script", "style"]):
        script.extract()    # rip it out
        # get text
    text = soup.get_text()
    # break into lines and remove leading and trailing space on each
    lines = (line.strip() for line in text.splitlines())
    # break multi-headlines into a line each
    chunks = (phrase.strip() for line in lines for phrase in line.split("  "))
    # drop blank lines
    text = '\n'.join(chunk for chunk in chunks if chunk)
    tableau = text.split('\n')
    return text, tableau


# ___________________________________Search_title_desc_url________________________________
extensions = ["tn", "fr", "com", "dz", "it"]
# This data was created by using the curl method explained above
headers_list = [
    # Firefox 77 Mac
    {
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:77.0) Gecko/20100101 Firefox/77.0",
        "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language": "en-US,en;q=0.5",
        "Referer": "https://www.google.com/",
        "DNT": "1",
        "Connection": "keep-alive",
        "Upgrade-Insecure-Requests": "1"
    },
    # Firefox 77 Windows
    {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:77.0) Gecko/20100101 Firefox/77.0",
        "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language": "en-US,en;q=0.5",
        "Accept-Encoding": "gzip, deflate, br",
        "Referer": "https://www.google.com/",
        "DNT": "1",
        "Connection": "keep-alive",
        "Upgrade-Insecure-Requests": "1"
    },
    # Chrome 83 Mac
    {
        "Connection": "keep-alive",
        "DNT": "1",
        "Upgrade-Insecure-Requests": "1",
        "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36",
        "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "Sec-Fetch-Site": "none",
        "Sec-Fetch-Mode": "navigate",
        "Sec-Fetch-Dest": "document",
        "Referer": "https://www.google.com/",
        "Accept-Encoding": "gzip, deflate, br",
        "Accept-Language": "en-GB,en-US;q=0.9,en;q=0.8"
    },
    # Chrome 83 Windows
    {
        "Connection": "keep-alive",
        "Upgrade-Insecure-Requests": "1",
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36",
        "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "Sec-Fetch-Site": "same-origin",
        "Sec-Fetch-Mode": "navigate",
        "Sec-Fetch-User": "?1",
        "Sec-Fetch-Dest": "document",
        "Referer": "https://www.google.com/",
        "Accept-Encoding": "gzip, deflate, br",
        "Accept-Language": "en-US,en;q=0.9"
    }]
# Create ordered dict from Headers above
ordered_headers_list = []
for headers in headers_list:
    h = OrderedDict()
    for header, value in headers.items():
        h[header] = value
    ordered_headers_list.append(h)


def recherche(query):
    query = urllib.parse.quote_plus(query)  # Format into URL encoding
    number_result = 8
    ce_header = random.choice(headers_list)
    # print(ce_header)
    #ua = random.choice(user_agent_list)
    google_url = "https://www.google." + \
        random.choice(extensions)+"/search?q=" + query + \
        "&safe=active&num=" + str(number_result)
    #print( google_url )
    response = requests.get(google_url, ce_header)
    # print(response.text)
    soup = BeautifulSoup(response.text, "html.parser")
    result_div = soup.find_all('div', attrs={'class': 'ZINbbc'})
    links = []
    titles = []
    descriptions = []
    for r in result_div:
        # Checks if each element is present, else, raise exception
        try:
            link = r.find('a', href=True)
            title = r.find('div', attrs={'class': 'vvjwJb'}).get_text()
            description = r.find('div', attrs={'class': 's3v9rd'}).get_text()
            #description = r.find('span', attrs={'class':'st'}).get_text()
            # Check to make sure everything is present before appending
            if link != '' and title != '' and description != '':
                links.append(link['href'])
                titles.append(title)
                descriptions.append(description)
        # Next loop if one element is not present
        except:
            continue
    return(links, titles, descriptions)
# ___________________________________URL_OK________________________________________________


def url_ok(url):
    try:
        r = requests.head(url)
        print(r.status_code)
        return r.status_code == 200
    except:
        return False
# ___________________________________domaine_______________________________________________


def domaine_url(k):
    index = k.index('w')
    urlg = k[index+4:len(k)]
    index = urlg.index('/')
    urlg = urlg[0:index]
    return urlg
# ___________________________________tags__________________________________________________


def tags(resp):
    html = resp.text
    soup = BeautifulSoup(html, 'lxml')
    tags = soup.find_all('loc')
    random.shuffle(tags)
    return tags
# ___________________________________Inserer_page__________________________________________


def inserer_page(connection, reference, att, cursor):
    cursor.execute(
        'INSERT INTO site_page (site_id, url) VALUES(%s,%s)', reference)
    connection.commit()
    cursor.execute(
        """SELECT id,site_id,url FROM site_page WHERE url=%s AND site_id=%s""", att)
    pages = cursor.fetchall()
    return pages
# ___________________________________Select_page____________________________________________


def select_page(att, cursor):
    cursor.execute(
        """SELECT id,site_id,url FROM site_page WHERE url=%s AND site_id=%s""", att)
    pages = cursor.fetchall()
    return pages
# ___________________________________Inserer_content_______________________________________


def inserer_content(tableau, page_id):
    for content in tableau:
        cursor.execute(
            'INSERT INTO content (page_id, text) VALUES(%s,%s)', (page_id, content))
        connection.commit()
# ___________________________________Select_contents_selon_Page_____________________________


def select_contents_page(att, cursor):
    cursor.execute("""SELECT * FROM content WHERE page_id=%s """, att)
    contents = cursor.fetchall()
    return contents
# ___________________________________Select_content_selon_page_&_txt________________________


def select_content_page_txt(att, cursor):
    cursor.execute(
        """SELECT * FROM content WHERE page_id=%s AND text=%s""", att)
    contents = cursor.fetchall()
    return contents
# __________________________Traitement_Recherche_content____________________________________


def recherche_content(content, txt, Max_similarity, url):
    try:
        query = '"'+txt+'"'
        i = 0
        if(len(txt) >= 100):
            time.sleep(10)
            print('Recherche_______________')
            att0 = (content[0],)
            cursor.execute(
                """SELECT * FROM content_plagiat WHERE content_id=%s """, att0)
            contentP1 = cursor.fetchall()
            if(contentP1 == []):
                links, titles, descriptions = recherche(
                    query)
                time.sleep(10)
                for j in range(len(links)):
                    if (urlg not in links[j]):
                        #i = i+1
                        similarite = functionS(
                            txt, descriptions[j])
                        print('similarity====')
                        print(similarite)
                        if(similarite > Max_similarite):
                            i = i+1
                            urlToCheck = links[j].replace(
                                "/url?q=", "")
                            urlToCheck = urlToCheck.split(
                                "&sa=")
                            urlToCheck = urlToCheck[0]
                            if(url_ok(urlToCheck)):
                                # ____________________________Inserer_Content_plagia_________________________
                                att0 = (
                                    content[0], descriptions[j])
                                cursor.execute(
                                    """SELECT * FROM content_plagiat WHERE content_id=%s AND description=%s""", att0)
                                contentP = cursor.fetchall()
                                print('plagiat')
                                print(contentP)
                                if(contentP == []):
                                    att1 = (
                                        content[0], urlToCheck, descriptions[j], similarite)
                                    cursor.execute(
                                        'INSERT INTO content_plagiat (content_id,url,description,plagiat) VALUES(%s,%s,%s,%s)', att1)
                                    connection.commit()
                                # ____________________________Remplir_champ_plagia_dans_page___________________
                                att2 = (1, url)
                                cursor.execute(
                                    """UPDATE site_page SET plagiat=%s WHERE url=%s""", att2)
                                connection.commit()
    except urllib.error.HTTPError:
        time.sleep(5)


# ___________________________________Traitement____________________________________________
try:
    cursor.execute("""SELECT * FROM Site """)
    rows = cursor.fetchall()
    # _________Parcourir_les_Sites
    for row in rows:
        print(row)
        k = row[2]  # ____domaine
        urlg = domaine_url(k)
        resp = requests.get(row[3])  # ____tags
        tags = tags(resp)
        for tag in tags:
            url = tag.text
            try:
                att = (url, row[0])
                pages = select_page(att, cursor)
                if pages == []:
                    reference = (row[0], url)
                    att = (url, row[0])
                    # __Inserer_page_d'un_site
                    pages = inserer_page(connection, reference, att, cursor)
                print(pages)
                page_id = pages[0][0]
                att = (page_id,)
                # ___select_contents_selon_page
                contents = select_contents_page(att, cursor)
                text, tableau = convText(url)
                if(contents == []):
                    # ___Inserer_Content_d'une_page
                    inserer_content(tableau, page_id)
                for content in tableau:
                    att = (page_id, content)
                    # ___Select_content_selon_page_&_txt
                    contents = select_content_page_txt(att, cursor)
                    if(contents):
                        content = contents[0]
                        txt = content[2]
                        Max_similarite = 50
                        # ____Traitement_de_recherche
                        recherche_content(content, txt, Max_similarite, url)
            except:
                print('error1')
except:
    print('error2')
