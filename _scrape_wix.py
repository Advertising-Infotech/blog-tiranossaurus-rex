#!/usr/bin/env python3
"""Scrape all posts from the Wix blog at trexhacker.wixsite.com/tiranossaurusrex"""

import os
import re
import json
import time
import traceback
import requests
from bs4 import BeautifulSoup
from concurrent.futures import ThreadPoolExecutor, as_completed
from urllib.parse import urlparse
from datetime import datetime

BASE_DIR = r'C:\Users\lagar\OneDrive\Área de Trabalho 2024\BackUp\Advertising TI & CS\Projetos\Blog Tiranossaurus Rex\wordpress'
IMAGES_DIR = os.path.join(BASE_DIR, 'wp-content', 'themes', 'tiranossaurusrex', 'images')
QUERY_FILE = os.path.join(BASE_DIR, 'wp-query.json')
ATTACHMENTS_FILE = os.path.join(BASE_DIR, 'wp-attachments.json')
WIX_URL = 'https://trexhacker.wixsite.com/tiranossaurusrex'
LISTING_BASE = WIX_URL + '/inicio'
TOTAL_PAGES = 95
MAX_WORKERS = 10
REQUEST_TIMEOUT = 30

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language': 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
}

MONTH_MAP = {
    'jan': 1, 'jan.': 1, 'janeiro': 1,
    'fev': 2, 'fev.': 2, 'fevereiro': 2,
    'mar': 3, 'mar.': 3, 'marco': 3,
    'abr': 4, 'abr.': 4, 'abril': 4,
    'mai': 5, 'mai.': 5, 'maio': 5,
    'jun': 6, 'jun.': 6, 'junho': 6,
    'jul': 7, 'jul.': 7, 'julho': 7,
    'ago': 8, 'ago.': 8, 'agosto': 8,
    'set': 9, 'set.': 9, 'setembro': 9,
    'out': 10, 'out.': 10, 'outubro': 10,
    'nov': 11, 'nov.': 11, 'novembro': 11,
    'dez': 12, 'dez.': 12, 'dezembro': 12,
}

CATEGORIES = {
    1: {"name": "Politica", "keywords": ["vereador", "prefeito", "eleicao", "camara municipal", "partido politico", "deputado", "governador", "caiado", "pellozo", "gestao publica", "presidente da republica", "votacao", "urnas"]},
    2: {"name": "Comportamento", "keywords": ["comportamento", "tendencia", "estilo de vida"]},
    3: {"name": "Cultura", "keywords": ["cultura", "musica", "show", "teatro", "danca", "exposicao", "literatura", "documentario", "curta-metragem", "cinema", "festa tradicional", "coral municipal", "galeria cultural", "artesanato"]},
    4: {"name": "Saude", "keywords": ["hospital municipal", "vacinacao", "dengue", "uti", "posto de saude", "ubs", "campanha de saude", "medicamento", "gripe", "sus"]},
    5: {"name": "Utilidade publica", "keywords": ["carteira de identidade", "cin", "inscricao", "prazo", "beneficio", "auxilio", "guia de servicos", "como emitir"]},
    6: {"name": "Municipalismo", "keywords": ["prefeitura de senador canedo", "senador canedo", "canedenses", "secretaria municipal", "servidor publico", "municipio", "gestao municipal"]},
    7: {"name": "Esportes", "keywords": ["jogos dos servidores", "artes marciais", "atleta", "campeonato", "futebol", "judo", "jiu-jitsu", "capoeira", "karate", "natacao", "vôlei", "pedal", "corrida"]},
    8: {"name": "Meio Ambiente", "keywords": ["coleta seletiva", "reflorestamento", "meio ambiente", "preservacao ambiental", "nascentes", "reciclagem", "amma"]},
    9: {"name": "Judiciario", "keywords": ["tribunal", "juiz", "sentenca", "processo judicial", "acao judicial", "justica"]},
    10: {"name": "Institucional", "keywords": ["institucional"]},
    11: {"name": "Policia", "keywords": ["policia militar", "policia civil", "delegacia", "prisao", "trafico de drogas", "operacao policial", "gcm", "guarda municipal", "violencia domestica", "suspeito preso"]},
    12: {"name": "Social", "keywords": ["campanha do agasalho", "cobertor", "doacao", "voluntario", "assistencia social", "creas", "cras", "maes atipicas", "moradia popular", "minha casa minha vida", "regularizacao fundiaria", "solidariedade"]},
    13: {"name": "Infraestrutura", "keywords": ["pavimentacao", "asfalto", "galeria pluvial", "iluminacao publica", "revitalizacao de pracas", "semaforo", "obras de infraestrutura"]},
    14: {"name": "Empreendedorismo", "keywords": ["feira do produtor", "empreendedorismo", "microempreendedor", "mei", "capacitacao profissional", "cursos gratuitos", "senai", "senac", "pequenos negocios"]},
    15: {"name": "Turismo", "keywords": ["turismo rural", "atracao turistica", "roteiro turistico"]},
    16: {"name": "Opiniao", "keywords": ["enquete", "opiniao", "artigo de opiniao", "reflexao", "ponto de vista"]},
    17: {"name": "Ciencia", "keywords": ["pesquisa cientifica", "estudo", "descoberta"]},
    18: {"name": "Economia", "keywords": ["economia local", "geracao de empregos", "renda", "investimento", "orcamento municipal", "pib"]},
    19: {"name": "Espiritualidade", "keywords": ["principe andre luis", "papa francisco", "igreja catolica", "espiritualidade", "religiao", "illuminati", "papa"]},
    20: {"name": "Arqueologia", "keywords": ["fossil", "dinossauro", "tiranossauro", "paleontologia", "arqueologia"]},
    21: {"name": "Educacao", "keywords": ["escola municipal", "professor", "aluno", "olimpiada de matematica", "obmep", "libras", "educacao municipal", "rede municipal de ensino"]},
    22: {"name": "Seguranca", "keywords": ["videomonitoramento", "base comunitaria", "patrulha", "ronda"]},
    23: {"name": "Saneamento", "keywords": ["saneamento basico", "agua e esgoto", "tratamento de agua"]},
    24: {"name": "Atualidades", "keywords": []},
    25: {"name": "Logistica", "keywords": ["logistica", "transporte"]},
    26: {"name": "Info Tech", "keywords": ["tecnologia", "internet", "aplicativo", "app", "redes sociais", "inteligencia artificial", "podcast", "podvim", "bill gates", "whatsapp"]},
    27: {"name": "Anuncios", "keywords": ["anuncio", "publicidade", "divulgacao", "patrocinio"]},
}

session = requests.Session()
session.headers.update(HEADERS)


def fetch(url, retries=3):
    for i in range(retries):
        try:
            resp = session.get(url, timeout=REQUEST_TIMEOUT)
            resp.raise_for_status()
            return resp
        except requests.exceptions.RequestException as e:
            if i < retries - 1:
                time.sleep(2 ** i)
            else:
                raise


def parse_date_pt(date_str):
    if not date_str:
        return None
    date_str = date_str.strip().lower()
    m = re.match(r'(\d{1,2})\s+de\s+(\S+?)\s+de\s+(\d{4})', date_str)
    if m:
        day = int(m.group(1))
        month_str = m.group(2).rstrip('.')
        year = int(m.group(3))
        month = MONTH_MAP.get(month_str)
        if month:
            return datetime(year, month, day)
    m2 = re.match(r'(\d{4})-(\d{2})-(\d{2})', date_str)
    if m2:
        return datetime(int(m2.group(1)), int(m2.group(2)), int(m2.group(3)))
    return None


def slugify(text):
    text = text.lower().strip()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[-\s]+', '-', text)
    return text.strip('-')


def extract_wix_hash(img_url):
    if not img_url:
        return None
    m = re.search(r'/media/([a-f0-9]+(?:_[a-f0-9]+)?)', img_url, re.IGNORECASE)
    if m:
        return m.group(1)
    return None


def get_raw_image_url(img_url):
    if not img_url:
        return None
    base = img_url.split('/v1/')[0]
    if '%7Emv2' in base:
        base = base.split('%7Emv2')[0] + '~mv2'
    if not re.search(r'\.(jpg|jpeg|png|gif|webp)$', base, re.IGNORECASE):
        base = base.rstrip('/') + '.jpg'
    return base


def guess_categories(title, content):
    text = (title + ' ' + content).lower()
    matched = []
    for cid, cat in CATEGORIES.items():
        if not cat['keywords']:
            continue
        for kw in cat['keywords']:
            if kw.lower() in text:
                matched.append(cid)
                break
    if not matched:
        matched.append(24)
    return matched


def load_json(filepath):
    if not os.path.exists(filepath):
        return None
    for enc in ('utf-8', 'cp1252', 'latin-1'):
        try:
            with open(filepath, 'r', encoding=enc) as f:
                return json.load(f)
        except (json.JSONDecodeError, UnicodeDecodeError):
            continue
    return None


def save_json(filepath, data):
    with open(filepath, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)


def scrape_listing_page(page_num):
    if page_num == 1:
        url = LISTING_BASE
    else:
        url = f'{LISTING_BASE}/page/{page_num}'

    try:
        resp = fetch(url)
    except Exception as e:
        print(f'  [Page {page_num}] Fetch ERROR: {e}')
        return []

    soup = BeautifulSoup(resp.text, 'html.parser')
    links = soup.select('a[href*="/post/"]')
    if not links:
        return []

    posts = []
    seen_urls = set()

    for a in links:
        href = a.get('href', '')
        if not href or href in seen_urls:
            continue
        seen_urls.add(href)

        h = a.find(['h1', 'h2', 'h3', 'h4'])
        title = (h.get_text(strip=True) if h else a.get_text(strip=True)).strip()
        if not title:
            continue

        path = urlparse(href).path

        container = a.find_parent('div', class_=lambda c: c and 'uYL9xS' in str(c))
        if not container:
            container = a.parent

        date_str = ''
        reading_time = 1
        spans = container.find_all('span')
        for span in spans:
            txt = span.get_text(strip=True)
            if not txt:
                continue
            m = re.search(r'(\d+)\s*min', txt)
            if m:
                reading_time = int(m.group(1))
            if re.match(r'\d{1,2}\s+de\s+\S', txt):
                date_str = txt

        posts.append({
            'url': href,
            'path': path,
            'title': title,
            'date_str': date_str,
            'reading_time': reading_time,
        })

    print(f'  [Page {page_num}] {len(posts)} posts')
    return posts


def extract_post_content(post_url, post_title):
    try:
        resp = fetch(post_url)
    except Exception as e:
        raise Exception(f'Fetch failed: {e}')

    soup = BeautifulSoup(resp.text, 'html.parser')
    body = soup.find('body')
    if body is None:
        raise Exception('No body tag')

    lines = body.get_text(separator='\n', strip=True).split('\n')
    lines = [l.strip() for l in lines if l.strip()]

    title_lower = post_title.lower().strip()

    content_start = -1
    for i, line in enumerate(lines):
        ll = line.lower().strip()
        if ll == title_lower or (len(title_lower) > 15 and title_lower[:25] in ll):
            content_start = i
            break

    if content_start < 0:
        for i, line in enumerate(lines):
            if 'min de leitura' in line.lower():
                content_start = i
                break

    if content_start < 0:
        content_start = 12

    content_end = len(lines)
    for i in range(content_start, len(lines)):
        ll = lines[i].lower().strip()
        if any(x in ll for x in ['posts recentes', 'ver tudo', 'compartilhar', 'siga-nos', 'ultimas do blog']):
            content_end = i
            break
        if ll == 'tiranossaurus rex' and i > content_start + 5:
            content_end = i
            break

    content_lines = lines[content_start:content_end]
    skip_phrases = [
        'top of page', 'este site foi criado no wix', 'crie o seu hoje',
        'use tab to navigate', 'tiranossaurus rex', 'login', 'more',
        'contato', 'members', 'interativo'
    ]
    cleaned = []
    for line in content_lines:
        ll = line.lower().strip()
        if not ll:
            continue
        if any(sp in ll for sp in skip_phrases):
            continue
        if re.match(r'^\d{1,2} de \S+ de \d{4}$', ll):
            continue
        if re.match(r'^\d+ min de leitura$', ll):
            continue
        if ll == post_title.lower().strip():
            continue
        cleaned.append(line)

    content = ' '.join(cleaned)
    content = re.sub(r'\s+', ' ', content).strip()

    images = []
    seen_hashes = set()
    for img in body.find_all('img'):
        src = (img.get('src', '') or img.get('data-src', '') or '').strip()
        if 'wixstatic.com/media/' not in src:
            continue
        img_hash = extract_wix_hash(src)
        if img_hash and img_hash not in seen_hashes:
            seen_hashes.add(img_hash)
            try:
                w = int(img.get('width', 0))
            except (ValueError, TypeError):
                w = 0
            if w > 100 or (img_hash and '~mv2' in src):
                images.append({'url': src, 'hash': img_hash})

    category_ids = guess_categories(post_title, content)

    return {
        'content': content,
        'images': images,
        'categories': category_ids,
    }


def download_image(img_url, post_title, existing_hashes):
    if not img_url or 'wixstatic.com/media/' not in img_url:
        return None

    img_hash = extract_wix_hash(img_url)
    raw_url = get_raw_image_url(img_url)
    url_lower = raw_url.lower() if raw_url else ''
    ext = '.jpg'
    for e in ['.png', '.gif', '.jpeg', '.webp']:
        if e in url_lower:
            ext = e
            break

    slug = slugify(post_title)[:60]
    if not slug:
        slug = 'image'
    if img_hash:
        slug = slug[:55]
        filename = f'{slug}-{img_hash[:8]}{ext}'
    else:
        filename = f'{slug}{ext}'
    filepath = os.path.join(IMAGES_DIR, filename)

    if os.path.exists(filepath):
        return filename

    if img_hash and img_hash in existing_hashes:
        src_name = existing_hashes[img_hash]
        src_path = os.path.join(IMAGES_DIR, src_name)
        if os.path.exists(src_path):
            import shutil
            shutil.copy2(src_path, filepath)
            return filename
        del existing_hashes[img_hash]

    try:
        resp = requests.get(raw_url, headers=HEADERS, timeout=30)
        resp.raise_for_status()
        with open(filepath, 'wb') as f:
            f.write(resp.content)
        if img_hash:
            existing_hashes[img_hash] = filename
        return filename
    except Exception:
        alt_url = img_url.split('/v1/')[0]
        if alt_url and alt_url != raw_url:
            try:
                resp = requests.get(alt_url, headers=HEADERS, timeout=30)
                resp.raise_for_status()
                with open(filepath, 'wb') as f:
                    f.write(resp.content)
                if img_hash:
                    existing_hashes[img_hash] = filename
                return filename
            except Exception:
                pass
        return None


def main():
    os.makedirs(IMAGES_DIR, exist_ok=True)

    query_data = load_json(QUERY_FILE)
    if query_data is None:
        query_data = {'posts': [], 'total_pages': 0, 'page': 1}

    attach_data = load_json(ATTACHMENTS_FILE)
    if attach_data is None:
        attach_data = {'attachments': []}

    existing_titles = set()
    for p in query_data.get('posts', []):
        t = p.get('title', {}).get('rendered', '').strip().lower()
        if t:
            existing_titles.add(t)

    next_post_id = max((p.get('id', 0) for p in query_data.get('posts', [])), default=0) + 1
    next_attach_id = max((a.get('id', 0) for a in attach_data.get('attachments', [])), default=0) + 1

    existing_hashes = {}

    print(f'Existing posts: {len(existing_titles)}')
    print(f'Scraping {TOTAL_PAGES} listing pages (12 posts each, ~1140 total)...')

    all_post_meta = []
    total_found = 0

    for page_num in range(1, TOTAL_PAGES + 1):
        print(f'\nPage {page_num}/{TOTAL_PAGES}...')
        posts = scrape_listing_page(page_num)
        if not posts:
            print(f'  No posts found on page {page_num}, stopping.')
            break

        for p in posts:
            total_found += 1
            key = p['title'].lower().strip()
            if key in existing_titles:
                continue
            existing_titles.add(key)
            all_post_meta.append(p)

    print(f'\n{"="*60}')
    print(f'Total unique posts across listing pages: {total_found}')
    print(f'New posts to process: {len(all_post_meta)}')

    if not all_post_meta:
        print('No new posts to add.')
        return

    print(f'\nFetching full content for {len(all_post_meta)} posts...')

    enriched_posts = []
    fail_count = 0

    with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
        fut_map = {}
        for p in all_post_meta:
            fut = executor.submit(extract_post_content, p['url'], p['title'])
            fut_map[fut] = p

        done = 0
        for future in as_completed(fut_map):
            done += 1
            p = fut_map[future]
            try:
                result = future.result()
                enriched_posts.append({
                    **p,
                    'content': result['content'],
                    'page_images': result['images'],
                    'categories': result['categories'],
                })
                if done % 25 == 0 or done == len(fut_map) or done == 1:
                    print(f'  Fetched {done}/{len(fut_map)} posts (failures: {fail_count})')
            except Exception as e:
                fail_count += 1
                enriched_posts.append({
                    **p,
                    'content': p['title'],
                    'page_images': [],
                    'categories': [24],
                })
                if fail_count <= 5:
                    print(f'  ERROR on post {done}: "{p["title"][:40]}" - {str(e)[:80]}')

    print(f'\nDone fetching. Success: {len(enriched_posts) - fail_count}, Failed: {fail_count}')

    print(f'\nDownloading images and building JSON...')
    new_query_posts = []
    new_attach_entries = []

    for p in enriched_posts:
        title = p['title']
        content = p['content']
        if not content or len(content) < 20:
            content = title

        date_iso = None
        if p.get('date_str'):
            parsed = parse_date_pt(p['date_str'])
            if parsed:
                date_iso = parsed.strftime('%Y-%m-%d')
        if not date_iso:
            date_iso = datetime.now().strftime('%Y-%m-%d')

        image_filename = None
        candidate_images = []
        if p.get('page_images'):
            candidate_images = [img['url'] for img in p['page_images']]

        for candidate_url in candidate_images:
            image_filename = download_image(candidate_url, title, existing_hashes)
            if image_filename:
                break

        attach_id = None
        if image_filename:
            attach_id = next_attach_id
            next_attach_id += 1
            attach_entry = {
                'id': attach_id,
                'source_url': f'wp-content/themes/tiranossaurusrex/images/{image_filename}',
                'title': title,
            }
            new_attach_entries.append(attach_entry)
            attach_data['attachments'].append(attach_entry)

        cat_ids = p.get('categories', guess_categories(title, content))

        post_entry = {
            'id': next_post_id,
            'title': {'rendered': title},
            'content': {'rendered': content},
            'date': date_iso,
            'featured_media': attach_id or 1,
            'categories': cat_ids,
            'author': 'Tiranossaurus Rex',
            'reading_time': p['reading_time'],
        }
        new_query_posts.append(post_entry)
        query_data['posts'].append(post_entry)
        next_post_id += 1

    total_posts = len(query_data['posts'])
    calc_pages = max(1, (total_posts + 9) // 10)
    query_data['total_pages'] = calc_pages
    query_data['page'] = 1

    save_json(QUERY_FILE, query_data)
    save_json(ATTACHMENTS_FILE, attach_data)

    print(f'\n{"="*60}')
    print(f'SCRAPING COMPLETE!')
    print(f'  Total posts in wp-query.json: {total_posts}')
    print(f'  New posts added: {len(new_query_posts)}')
    print(f'  New images downloaded: {len(new_attach_entries)}')
    print(f'  Failed post fetches: {fail_count}')
    print(f'  Total attachments: {len(attach_data["attachments"])}')
    print(f'{"="*60}')


if __name__ == '__main__':
    main()
