#!/usr/bin/env python3
"""Debug script data extraction v6"""
import requests, json
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
r = requests.get('https://trexhacker.wixsite.com/tiranossaurusrex/inicio', headers=headers, timeout=30)
soup = BeautifulSoup(r.text, 'html.parser')

for s in soup.find_all('script'):
    if s.string and 'firstPublishedDate' in s.string:
        txt = s.string
        outer = json.loads(txt)
        awd = outer['appsWarmupData']
        blog_app = awd['14bcded7-0066-7c35-14d7-466cb3f09103']
        feed_key = list(blog_app.keys())[0]
        feed_str = blog_app[feed_key]
        
        # Parse the string as JSON
        feed_data = json.loads(feed_str)
        print(f'Feed data type: {type(feed_data).__name__}')
        print(f'Top keys: {list(feed_data.keys())[:10]}')
        
        fr = feed_data.get('feedResponse', {})
        pfp = fr.get('postFeedPage', {})
        posts_container = pfp.get('posts', {})
        posts = posts_container.get('posts', [])
        paging = posts_container.get('paging', {})
        print(f'Posts: {len(posts)}')
        print(f'Paging: {json.dumps(paging, ensure_ascii=False)[:200]}')
        
        for p in posts[:2]:
            print(f'  ID: {p.get("id","")}')
            print(f'  Title: {p.get("title","")[:80]}')
            print(f'  Date: {p.get("firstPublishedDate","")}')
            print(f'  Excerpt len: {len(p.get("excerpt",""))}')
            print(f'  Excerpt: {p.get("excerpt","")[:150]}')
            print(f'  Image: {p.get("content","")[:120]}')
            print(f'  Slug: {p.get("slug","")}')
            print(f'  CategoryIds: {p.get("categoryIds",[])}')
            print()
        break
