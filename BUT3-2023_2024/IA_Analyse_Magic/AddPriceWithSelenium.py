from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.wait import WebDriverWait

import csv
import pandas as pd

df = pd.read_csv('.\\Data\\cards.csv')
df.drop_duplicates(subset="name", keep='first', inplace=True)

df.drop(df[df['types'] != 'Creature'].index, inplace = True)
df = df[["name","rarity","manaValue","manaCost","frameVersion","printings","power","toughness","keywords"]]

driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)
f = open('./csv_file', 'w')
writer = csv.writer(f)
driver.get('https://www.cardmarket.com/fr/Magic')

for CardName in df.tail(50)['name']:

    wait.until(EC.element_to_be_clickable((By.ID, 'ProductSearchInput')))
    driver.find_element(By.ID,'ProductSearchInput').send_keys(CardName)

    wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "#AutoCompleteResult[style*='visibility: visible'] a")))
    driver.find_element(By.CSS_SELECTOR,'#AutoCompleteResult a').click()
    
    # Price Trend Tendance des prix
    wait.until(EC.element_to_be_clickable((By.XPATH,'//*[contains(text(),"Tendance des prix")]')))
    Price = driver.find_element(By.XPATH,'//*[contains(text(),"Tendance des prix")]/following-sibling::node()').text
    Price = Price.replace(" €",'')
    Price = Price.replace(",",'.')
    Price = float(Price)

    writer.writerow([CardName,Price])
driver.close()