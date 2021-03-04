from bs4 import BeautifulSoup
import xml.etree.ElementTree as ET
import requests,time,os,datetime
from urllib.request import urlopen
from urllib import error
import urllib 
from mysql.connector import Error
import mysql.connector
import requests
import urllib
import random
from fake_useragent import UserAgent
from bs4 import BeautifulSoup
from urllib.request import urlopen
from urllib import error
import time

def checknoindex(url1):
    #html = requests.get(url).content
    try:
        i="%"
        if(i not in url1):
            url1 = urllib.parse.quote( url1 ) 
        url1 = url1.replace("https%3A//" , "https://")
        html = urlopen(url1).read()
        soup = BeautifulSoup(html,"html.parser")
        text =soup.find_all("meta", content="noindex")
        return text
    except:
        print('error!')
#convText("https://www.turquiesante.com/medecine-laboratoire-135")

def ask_for_url(url):
    """time.sleep(1)
    print('write your domain')
    global url
    url= input() #raw_input()"""
    try:
        global response
        checking=url[-1]
        if checking =="/":
            response=requests.get(url)
            start_crawling()
        else:
            url=url+"/"
            response=requests.get(url)
            start_crawling()
    except:
        print('please write a valid url !')
def start_crawling():
    os.system('cls')
    print('crawling')
    time.sleep(3)
    global checked_links
    checked_links=[]
    #checki=checknoindex(url)
    #print(checki)
    #if(checki)==[]:
    checked_links.append(url)
    crawling_web_pages()
def crawling_web_pages():
    global responses
    control=0
    while control < len(checked_links):
        try:
            responses=requests.get(checked_links[control])
            source_code=responses.text
            soup=BeautifulSoup(source_code,"html.parser")
            new_links=[w['href'] for w in soup.findAll('a',href=True)]
            counter=0
            while counter <len(new_links):
                if "http" not in new_links[counter]:
                    verify =new_links[counter][0]
                    if verify=="/":
                        new_links[counter]=new_links[counter][:1].replace('/','')+new_links[counter][1:]
                        new_links[counter]=url+new_links[counter]
                        counter=counter+1
                    else:
                        new_links[counter]=url+new_links[counter]
                        counter=counter+1
                else:
                    counter=counter+1
            else:
                counter2=0
                while counter2<len(new_links):
                    if "#" not in new_links[counter2] and "malito" not in new_links[counter2] and ".jpg" not in new_links[counter2] and new_links[counter2] not in checked_links and url in new_links[counter2]:
                        checked_links.append(new_links[counter2])
                        os.system('cls')
                        print(str(control)+"/"+str(len(checked_links)))
                        print('')
                        print(str(control)+"Web Pages Crawled &"+str(len(checked_links))+"Web pages found")
                        print('')
                        print(new_links[counter2])
                        counter2=counter2 + 1
                    else:
                        counter2=counter2 + 1
                else:
                    os.system('cls')
                    print(str(control)+"/"+str(len(checked_links)))
                    print('')
                    print(str(control)+"Web Pages Crawled &"+str(len(checked_links))+"Web pages found")
                    print('')
                    print(new_links[control])
                    control=control + 1
        except:
            control=control + 1
    else:
        time.sleep(2)
        creating_sitemap()
def creating_sitemap():
    final_links=[]
    it=0
    count=0
    while count<len(checked_links):
        print(str(checked_links[count]))
        checki=checknoindex(str(checked_links[count]))
        if(checki)==[]:
            print(checki)
            final_links.append(checked_links[count])
            print(count)  
            it=it+1
            count=count + 1
        else:
            count=count + 1
    else:
        print('Creating sitemap')
        urlset=ET.Element("urlset",xmlns="http://www.sitemaps.org/schemas/sitemap/0.9")
        count=0
        while count<len(final_links):
            print(str(final_links[count]))
            checki=checknoindex(str(final_links[count]))
            urls= ET.SubElement(urlset,"url")
            ET.SubElement(urls,"loc",).text= str(final_links[count])
            count=count + 1
            print(count)
        else:
            tree= ET.ElementTree(urlset)
            tree.write("../doc/sitemap.xml")
            #nom+"sitemap.xml", encoding = 'utf-8', xml_declaration=True)
            print("your sitemap is ready! ")



def domaine_url(k):
    index = k.index('w')
    urlg = k[index+4:len(k)]
    index = urlg.index('.')
    urlg = urlg[0:index]
    return urlg

# ___________________________________Traitement____________________________________________
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='plagiarism_detection',
                                         user='root',
                                         password='')
    cursor = connection.cursor()
    #print('connection effectué !')
except mysql.connector.Error as error:
    print(error)
try:
    cursor.execute("""SELECT * FROM generate WHERE id = (SELECT MAX(id) FROM compare) """)
    rows = cursor.fetchall()
    row=rows[0]
    #print(row)
    global url
    url=rows[1]
    #url="https://www.chirurgie-esthetique-entunisie.com/"
    #url="https://www.turquiesante.com/"
    #url="https://www.chirurgieesthetiquetunisie.fr/"
    #url="https://www.sofirux.com/"
    #url="https://emobilehub.com/"
    ask_for_url(url)
except:
    print('erreur!')
