#!/usr/bin/env python3
"""Debug script data extraction v3"""
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
            # Explore structure
            for k in ['appsWarmupData', 'platform', 'ooi', 'builderComponentsWarmupData']:
                val = outer.get(k)
                if val:
                    print(f'{k}: {type(val).__name__}', end='')
                    if isinstance(val, dict):
                        print(f' keys={list(val.keys())[:10]}')
                        # Check if any key contains blog/post data
                        for k2, v2 in val.items():
                            if 'blog' in k2.lower() or 'post' in k2.lower():
                                print(f'  -> {k2}: {type(v2).__name__} len={len(str(v2))}')
                    elif isinstance(val, str):
                        print(f' len={len(val)}')
                        if '_POSTS' in val or 'firstPublishedDate' in val:
                            print(f'  Contains post data!')
                            inner = json.loads(val)
                            print(f'  Inner type: {type(inner).__name__}')
                            if isinstance(inner, dict):
                                print(f'  Inner keys: {list(inner.keys())[:10]}')
                    else:
                        print(f' len={len(str(val))}')
        except json.JSONDecodeError as e:
            print(f'Parse error: {e}')
        break
