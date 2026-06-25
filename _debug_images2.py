#!/usr/bin/env python3
"""Find featured images in listing page - style attrs, data attrs"""
import requests, re
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

containers = soup.find_all('div', class_=lambda c: c and 'uYL9xS' in str(c))

for container in containers[:2]:
    link = container.find('a', href=re.compile(r'/post/'))
    h = link.find('h2') if link else None
    title = h.get_text(strip=True)[:60] if h else 'N/A'
    print(f'Title: {title}')
    
    # Find ALL elements with style containing background-image
    for el in container.find_all(style=re.compile(r'background-image', re.I)):
        style = el.get('style', '')
        print(f'  Bg style: {style[:200]}')
    
    # Find ALL elements with style containing wixstatic
    for el in container.find_all(style=re.compile(r'wixstatic', re.I)):
        style = el.get('style', '')
        print(f'  Wix bg: {style[:200]}')
    
    # Look for data-* attributes
    for el in container.find_all(attrs={"data-hook": re.compile(r'thumbnail|image|cover', re.I)}):
        print(f'  Data hook: {el.attrs}')
    
    # Search for any attribute containing wixstatic
    for el in container.find_all(style=True):
        style = el.get('style', '')
        if 'static.wixstatic.com' in style:
            url = re.search(r'url\(["\']?(https?://[^"\'\)]+)', style)
            if url:
                print(f'  BG Image URL: {url.group(1)[:130]}')
    break

# Also check the overall page for image patterns related to posts
page_html = r.text
pattern = r'https?://static\.wixstatic\.com/media/[a-f0-9_.~%-]+\.(?:jpg|jpeg|png|gif|webp)'
all_imgs = re.findall(pattern, page_html, re.I)
unique_imgs = list(set(all_imgs))
print(f'\nTotal unique wixstatic image URLs on page: {len(unique_imgs)}')
for u in unique_imgs[:5]:
    if 'icon' not in u.lower() and 'logo' not in u.lower():
        print(f'  {u[:130]}')
