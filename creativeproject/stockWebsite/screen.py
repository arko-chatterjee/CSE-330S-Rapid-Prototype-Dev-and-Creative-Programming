from bs4 import BeautifulSoup as bsoup
from urllib.error import HTTPError
from urllib.request import Request, urlopen
from urllib.parse import quote
import pandas as pd
from flask import (
    Blueprint, g, render_template, request, url_for, Response
)
from werkzeug.exceptions import abort

bp = Blueprint('screen',__name__)

@bp.route('/',methods=('GET','POST'))
def index():
    ticker=''
    order=''
    formArgs = {}
    if request.method == 'POST' and request.form.get('search') is not None:
        formArgs['idx'] = request.form.get('indexes')
        formArgs['cap'] = request.form.get('mcap')
        formArgs['price'] = request.form.get('price')
        formArgs['avvol'] = request.form.get('avVol')
        formArgs['outstand'] = request.form.get('shareOutstand')
        formArgs['sm20'] = request.form.get('sma20')
        formArgs['sm50'] = request.form.get('sma50')
        formArgs['sm200'] = request.form.get('sma200')
        asce = request.form.get('asce')=='asce'
        ticker = request.form.get('tickerSearch')
        order = request.form.get('order')
        screen_data = screen(modifiers=formArgs,asc=asce, ticker=ticker, order=order)
        formArgs['asce'] = request.form.get('asce')
        formArgs['page'] = request.form.get('page')
    else:
        screen_data = screen()
    return render_template('screen/index.html',column_names=screen_data.columns.values, row_data=list(screen_data.values.tolist()),
                           link_column="Ticker", zip=zip, args=formArgs, tck=ticker, ord=order)

@bp.route('/<string:ticker>')
def stockInfo(ticker):
    stock_data, news_data, links = getStock(ticker)
    return render_template('screen/stock.html', row_data_stock=list(stock_data.values.tolist()), ticker=ticker, row_data_news=list(news_data.values.tolist()),
                            link_column="Article", zip=zip, news_column_names=news_data.columns.values, links=links)

@bp.route('/download', methods=('GET','POST'))
def download():
    if request.method == 'POST':
        formArgs = {}
        formArgs['idx'] = request.form.get('idx')
        formArgs['cap'] = request.form.get('cap')
        formArgs['price'] = request.form.get('price')
        formArgs['avvol'] = request.form.get('avvol')
        formArgs['outstand'] = request.form.get('outstand')
        formArgs['sm20'] = request.form.get('sm20')
        formArgs['sm50'] = request.form.get('sm50')
        formArgs['sm200'] = request.form.get('sm200')
        asce = True
        if request.form.get('asce') is not None:
            asce = request.form.get('asce')=='asce'
        ticker = request.form.get('ticker')
        order = request.form.get('order')
        print(formArgs)
        screen_data = screen(modifiers=formArgs,asc=asce, ticker=ticker, order=order, download=True)
        return Response(screen_data.to_csv(),mimetype='text/csv',
        headers = {"Content-disposition":"attachment; filename=results.csv"})
    elif request.method == 'GET':
        ticker = request.args.get('ticker')
        stock_info = getStock(ticker=ticker, download=True)
        return Response(stock_info.to_csv(),mimetype='text/csv',
        headers = {"Content-disposition":"attachment; filename=results.csv"})

def screen(modifiers=None, page=1, order='ticker', asc=True, ticker=None, download = False):
    #building url to scrape data from
    url = 'https://finviz.com/screener.ashx?v=111&f='
    if modifiers is not None and modifiers['idx']:
        for v in modifiers.values():
            if not v.lower() == 'any':
                url = url + v + ','
    if ticker is not None:
        url = url + '&t=' + quote(ticker, safe='')
    url = url + '&o='
    if not asc:
        url = url + '-'
    url = url + order
    req = Request(url,headers={'User-Agent': 'Mozilla/5.0'})
    htmlcode = urlopen(req).read()
    soup = bsoup(htmlcode, 'html.parser')

    #scraping content from the data table to bring into program in a DataFrame
    tables = soup.find(id='screener-content').findAll('table')
    table = tables[3]
    rows = table.find_all(lambda tag: tag.name=='tr')
    builddf=pd.DataFrame()
    names=[]
    rowtitle=rows[0].find_all('td')
    names=names+[x.text for x in rowtitle]
    for i in range(1,len(rows)):
        out=[]
        td=rows[i].find_all('td')
        out=out+[x.text for x in td]
        builddf=builddf.append(pd.DataFrame(out).transpose())
    for i in range(len(names)):
        builddf = builddf.rename(columns={i:names[i]})

    if download:
        return builddf
    return builddf.drop(columns=['Industry','P/E']) if 'Industry' in builddf.columns and 'P/E' in builddf.columns else builddf

def getStock(ticker, download=False):
    url = 'https://finviz.com/quote.ashx?t=' + ticker.upper()
    req = Request(url,headers={'User-Agent': 'Mozilla/5.0'})
    htmlcode = None
    try:
        htmlcode = urlopen(req).read()
    except HTTPError: #this error is thrown when the stock ticker is invalid
        htmlcode = None
    if htmlcode is not None:
        soup = bsoup(htmlcode, 'html.parser')

        #getting fundamentals for a stock and filling tables
        fundtable = soup.find_all('table', {'class' : 'snapshot-table2'})[0]
        fundrows = fundtable.find_all(lambda tag: tag.name=='tr')
        funddf = pd.DataFrame()
        for i in range(len(fundrows)):
            out=[]
            td=fundrows[i].find_all('td')
            out=out+[x.text for x in td]
            funddf=funddf.append(pd.DataFrame(out).transpose())
        if download:
            return funddf
        #getting news for a stock and filling dataframe
        newstable = soup.find_all('table', {'class' : 'fullview-news-outer'})
        newsdf = pd.DataFrame()
        links = []
        if len(newstable) > 0:
            for a in soup.find_all('a', {'class' : 'tab-link-news'}):
                links.append(a['href'])
            newstable = newstable[0]
            newsrows = newstable.find_all(lambda tag: tag.name=='tr')
            for i in range(len(newsrows)):
                out=[]
                td = newsrows[i].find_all('td')
                out=out+[x.text for x in td]
                newsdf=newsdf.append(pd.DataFrame(out).transpose())
        newsdf=newsdf.rename(columns={0:'Date',1:'Article'})
        return (funddf, newsdf, links)
    else:
        return (pd.DataFrame(), pd.DataFrame(), [])

