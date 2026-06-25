#!/usr/bin/env python3
"""Debug script data extraction"""
import requests, re, json
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

for s in soup.find_all('script'):
    if s.string and 'firstPublishedDate' in s.string:
        txt = s.string
        print(f'Script length: {len(txt)}')
        print(f'First 200: {repr(txt[:200])}')
        print()
        
        # Look for posts array - find the pattern
        # The data might be in a JS string with escaped quotes
        idx = txt.find('"posts":{"posts"')
        if idx >= 0:
            print(f'Found posts.posts at {idx}')
            chunk = txt[idx:idx+500]
            print(f'Context: {repr(chunk[:500])}')
        
        # Try different patterns
        for pat in ['"posts":[', '"posts": [', '"posts":{', '"posts": {']:
            idx = txt.find(pat)
            if idx >= 0:
                print(f'Pattern "{pat}" found at {idx}')
        
        # Look for the actual post objects with escaped quotes
        idx = txt.find('\\"id\\"')
        if idx >= 0:
            print(f'Found escaped id at {idx}')
            print(f'Context: {repr(txt[idx-50:idx+300])}')
        
        # Check for single-backslash escaping
        idx2 = txt.find('\\"id":"')
        if idx2 >= 0:
            print(f'Found \\"id":" at {idx2}')
            print(f'Context: {repr(txt[idx2-30:idx2+200])}')
        
        # Check for triple-backslash
        idx3 = txt.find('\\\\\\"id\\\\\\"')
        if idx3 >= 0:
            print(f'Found triple-escaped at {idx3}')
        
        # Try to find the structure by looking at quotes and brackets
        break
