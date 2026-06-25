#!/usr/bin/env python3
"""Check if individual post page has more content"""
import requests, json, re
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}

# First, get a listing page to extract a post URL
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')
links = soup.select('a[href*="/post/"]')
href = links[1].get('href')  # second post: "Grande final dos Jogos dos Servidores"
print(f'Post URL: {href}')

# Fetch post page
s = requests.Session()
s.headers.update(headers)
s.get('https://trexhacker.wixsite.com/tiranossaurusrex', timeout=30)
r2 = s.get(href, timeout=60)
print(f'Status: {r2.status_code}, Size: {len(r2.text)} bytes')

soup2 = BeautifulSoup(r2.text, 'html.parser')

# Find text blocks in order, filtering for meaningful content
body = soup2.find('body')
if body:
    # Get all text, stripping whitespace
    text = body.get_text(separator='\n', strip=True)
    lines = [l.strip() for l in text.split('\n') if l.strip()]
    print(f'\nAll text lines ({len(lines)} lines):')
    for i, line in enumerate(lines):
        print(f'  [{i}] {line[:150]}')
        if i > 30:
            print(f'  ... ({len(lines) - i - 1} more lines)')
            break
