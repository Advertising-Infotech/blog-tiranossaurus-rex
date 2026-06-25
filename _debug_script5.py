#!/usr/bin/env python3
"""Debug script data extraction v5"""
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
        # Get the first (and only) key
        feed_key = list(blog_app.keys())[0]
        feed_data = blog_app[feed_key]
        print(f'Feed key: {feed_key}')
        print(f'Feed data type: {type(feed_data).__name__}')
        
        if isinstance(feed_data, dict):
            # Navigate to posts
            if 'feedResponse' in feed_data:
                fr = feed_data['feedResponse']
                if 'postFeedPage' in fr:
                    pfp = fr['postFeedPage']
                    if 'posts' in pfp:
                        posts_container = pfp['posts']
                        if 'posts' in posts_container:
                            posts = posts_container['posts']
                            print(f'Found {len(posts)} posts!')
                            for p in posts[:3]:
                                print(f'  Title: {p.get("title","?")[:80]}')
                                print(f'  Date: {p.get("firstPublishedDate","?")}')
                                print(f'  Excerpt len: {len(p.get("excerpt",""))}')
                                print(f'  Image: {p.get("content","")[:120]}')
                                print(f'  Categories: {p.get("categoryIds",[])}')
                                print()
                            # Also get paging info
                            print(f'Paging: {posts_container.get("paging",{})}')
        break
