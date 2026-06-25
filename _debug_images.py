#!/usr/bin/env python3
"""Find images in listing page - CSS backgrounds, lazy loads, etc"""
import requests, re
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

# Check grandparent containers
containers = soup.find_all('div', class_=lambda c: c and 'uYL9xS' in str(c))
print(f'Found {len(containers)} post containers')

for container in containers[:3]:
    link = container.find('a', href=re.compile(r'/post/'))
    h = link.find('h2') if link else None
    title = h.get_text(strip=True)[:60] if h else 'N/A'
    
    # Find ALL spans in the container
    spans = container.find_all('span')
    dates = []
    reading_times = []
    for s in spans:
        txt = s.get_text(strip=True)
        if txt and txt[0].isdigit() and 'de ' in txt:
            dates.append(txt)
        m = re.search(r'(\d+)\s*min', txt)
        if m:
            reading_times.append(m.group(0))
    
    print(f'\nTitle: {title}')
    print(f'  Dates: {dates}')
    print(f'  Reading times: {reading_times}')
    
    # Check for background images
    html = str(container)
    bg_matches = re.findall(r'background-image[^;]+url\([^)]+\)', html)
    for bg in bg_matches[:3]:
        print(f'  BG image: {bg[:150]}')
    
    # Check for data-src or srcset
    imgs = container.find_all('img')
    for img in imgs:
        attrs = {k: v for k, v in img.attrs.items() if k in ('src', 'data-src', 'srcset', 'data-srcset')}
        if attrs:
            print(f'  Img attrs: {attrs}')
    
    # Check for wixstatic URLs in any attribute
    wix_urls = re.findall(r"""https?://static\.wixstatic\.com/media/[^\s"']+""", html)
    unique_urls = list(set(wix_urls))
    for u in unique_urls[:5]:
        print(f'  WIX URL: {u[:130]}')
