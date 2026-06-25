#!/usr/bin/env python3
"""Debug script data extraction v4"""
import requests, re, json
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

for s in soup.find_all('script'):
    if s.string and 'firstPublishedDate' in s.string:
        txt = s.string
        outer = json.loads(txt)
        # Look at appsWarmupData
        awd = outer.get('appsWarmupData', {})
        for app_id, app_data in awd.items():
            print(f'App ID: {app_id}')
            if isinstance(app_data, str):
                print(f'  String, len={len(app_data)}')
                if 'firstPublishedDate' in app_data or 'posts' in app_data.lower():
                    print(f'  Contains post data!')
                    try:
                        inner = json.loads(app_data)
                        if isinstance(inner, dict):
                            print(f'  Inner keys: {list(inner.keys())[:15]}')
                            # Look for posts
                            for k, v in inner.items():
                                if 'post' in k.lower():
                                    print(f'    Key: {k}')
                                    if isinstance(v, dict):
                                        print(f'    Subkeys: {list(v.keys())[:15]}')
                    except:
                        print(f'  (not valid JSON)')
            elif isinstance(app_data, dict):
                print(f'  Dict, keys={list(app_data.keys())[:15]}')
                # Look for post data recursively
                def find_posts(d, depth=0):
                    if depth > 5:
                        return
                    for k, v in d.items():
                        if 'post' in k.lower() and isinstance(v, (dict, list)):
                            print(f'  {"  "*depth}{k}: {type(v).__name__}')
                            if isinstance(v, list) and len(v) > 0:
                                print(f'  {"  "*depth}  First item: {str(v[0])[:200]}')
                            if isinstance(v, dict):
                                find_posts(v, depth+1)
                        elif isinstance(v, dict):
                            find_posts(v, depth+1)
                find_posts(app_data)
        break
