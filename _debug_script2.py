#!/usr/bin/env python3
"""Debug script data extraction v2"""
import requests, re, json
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

for s in soup.find_all('script'):
    if s.string and 'firstPublishedDate' in s.string:
        txt = s.string
        try:
            outer = json.loads(txt)
            print(f'Top-level keys: {list(outer.keys())[:10]}')
            # Look for the key that contains "_POSTS"
            for k, v in outer.items():
                if '_POSTS' in k.upper() or 'posts' in k.lower():
                    print(f'Found candidate key: {k}')
                    if isinstance(v, str):
                        print(f'  Value type: string, length: {len(v)}')
                        try:
                            inner = json.loads(v)
                            print(f'  Inner keys: {list(inner.keys())}')
                            if 'feedResponse' in inner:
                                fr = inner['feedResponse']
                                if 'postFeedPage' in fr:
                                    pfp = fr['postFeedPage']
                                    if 'posts' in pfp:
                                        posts_data = pfp['posts']
                                        if 'posts' in posts_data:
                                            posts = posts_data['posts']
                                            print(f'  Found {len(posts)} posts!')
                                            for p in posts[:2]:
                                                print(f'    Title: {p.get("title","?")[:60]}')
                                            break
                        except json.JSONDecodeError as e:
                            print(f'  Inner JSON parse error: {e}')
        except json.JSONDecodeError as e:
            print(f'Outer JSON parse error: {e}')
        break
