#!/usr/bin/env python3
"""Debug listing page HTML structure for dates/images"""
import requests
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

links = soup.select('a[href*="/post/"]')
a = links[0]
href = a.get('href')

# Get the grandparent container that has post data
# Check multiple levels of parents
print('=== Link parent chain ===')
parent = a.parent
for i in range(5):
    if parent:
        cls = parent.get('class', [])
        tag = parent.name
        print(f'  Level {i}: <{tag} class={cls}>')
        print(f'    Children: {[c.name for c in parent.find_all(recursive=False)][:10]}')
        parent = parent.parent
    else:
        break

print()
print('=== Spans in sibling area ===')
parent = a.find_parent('div', class_=lambda c: c and 'JMCi2v' in str(c)) if a.find_parent('div', class_=lambda c: c and 'JMCi2v' in str(c)) else a.parent
print(f'Using parent: <{parent.name} class={parent.get("class","")}>')

# Search broader - look at all siblings and the container's next siblings
all_spans = parent.find_all('span')
print(f'Spans in parent: {len(all_spans)}')
for s in all_spans:
    txt = s.get_text(strip=True)
    print(f'  span: "{txt[:80]}"')

# Search the grandparent
gp = parent.parent
all_spans_gp = gp.find_all('span')
print(f'\nSpans in grandparent: {len(all_spans_gp)}')
for s in all_spans_gp[:15]:
    txt = s.get_text(strip=True)
    print(f'  span: "{txt[:80]}"')

# Look at the parent container more carefully
print(f'\n=== Grandparent HTML (first 1000 chars) ===')
print(str(gp)[:1000])

# Check where images are
print(f'\n=== Images in grandparent ===')
imgs = gp.find_all('img')
for img in imgs:
    src = img.get('src', '')[:120]
    cls = img.get('class', [])
    print(f'  img class={cls} src={src}')
