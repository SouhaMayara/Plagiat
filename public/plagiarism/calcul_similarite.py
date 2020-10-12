# Program to measure similarity between  
# two sentences using cosine similarity.
from urllib.request import urlopen
from urllib import error
import time
from bs4 import BeautifulSoup
#from google import search
import google
from googlesearch import search
import urllib
import requests 
import nltk
#nltk.download('all')
#nltk.download('punkt')
from nltk.corpus import stopwords 
from nltk.tokenize import word_tokenize 

# _____________________________________DB_connection____________________________________
import mysql.connector
from mysql.connector import Error

# X = input("Enter first string: ").lower() 
# Y = input("Enter second string: ").lower() 
def functionS(X:str,Y:str):
    # tokenization 
    X_list = word_tokenize(X)  
    Y_list = word_tokenize(Y) 
    # sw contains the list of stopwords 
    sw = stopwords.words('english','french')
    #sw=stopwords.words('french')
    #print (sw) 
    l1 =[];l2 =[] 
    # remove stop words from string 
    X_set = {w for w in X_list if not w in sw}  
    Y_set = {w for w in Y_list if not w in sw} 
    # form a set containing keywords of both strings  
    rvector = X_set.union(Y_set)  
    for w in rvector: 
        if w in X_set: l1.append(1) # create a vector 
        else: l1.append(0) 
        if w in Y_set: l2.append(1) 
        else: l2.append(0) 
    c = 0
    # cosine formula  
    for i in range(len(rvector)): 
            c+= l1[i]*l2[i] 
    try:
        #print((sum(l1)*sum(l2))**0.5)
        #print(c)
        cosine =(c / float((sum(l1)*sum(l2))**0.5))
        #print("similarity: ", cosine) 
    except ZeroDivisionError:
                print("devision By 0")
    cosine=cosine*100
    cosine=round(cosine,2)
    print(cosine)
    return cosine
def update(att,cursor):
    cursor.execute("""UPDATE compare SET calcul=%s WHERE id = (SELECT MAX(id) FROM compare)""", att)
    connection.commit()      

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
    cursor.execute("""SELECT * FROM compare WHERE id = (SELECT MAX(id) FROM compare) """)
    rows = cursor.fetchall()
    row=rows[0]
    #print(row)
    s=functionS(row[1],row[2])
    # ____________________________Remplir_champ_plagia_dans_page___________________
    att = (s,)
    update(att,cursor)
    
except:
    print('erreur!')

