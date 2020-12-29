from flask import (
    current_app, Blueprint, Response, request
)
from alpha_vantage.timeseries import TimeSeries
import pandas as pd
import matplotlib.pyplot as plot
import io
from matplotlib.backends.backend_agg import FigureCanvasAgg as FigureCanvas


bp = Blueprint('graph',__name__,url_prefix='/graph')

@bp.route('/daily', methods=('GET','POST'))
def dailygraph():
    if request.method == 'POST':
        ticker = request.form.get('ticker')
        ts, meta = getDailyTimeSeries(ticker)
        return makeGraph(ts,'date_time','2. high',f'Stock Chart for {ticker}')

@bp.route('/weekly', methods=('GET','POST'))
def weeklygraph():
    if request.method == 'POST':
        ticker = request.form.get('ticker')
        ts, meta = getWeeklyTimeSeries(ticker)
        return makeGraph(ts,'date_time','2. high',f'Stock Chart for {ticker}')

def getDailyTimeSeries(ticker, output='compact'):
    dailyts = TimeSeries(key=current_app.config['ALPHA_VANTAGE_KEY'], output_format='pandas')
    tsdata, metadata = dailyts.get_daily_adjusted(ticker,outputsize=output)
    tsdata['date_time']=tsdata.index
    return tsdata, metadata

def getWeeklyTimeSeries(ticker, output='compact'):
    weeklyts = TimeSeries(key=current_app.config['ALPHA_VANTAGE_KEY'], output_format='pandas')
    tsdata, metadata = weeklyts.get_weekly_adjusted(ticker)
    tsdata['date_time']=tsdata.index
    return tsdata, metadata

def makeGraph(tsdf, x,y,title): #https://techrando.com/2020/01/12/pulling-financial-time-series-data-into-python-some-free-options/
    fig, ax = plot.subplots()
    ax.plot_date(tsdf[x], tsdf[y], marker='', linestyle='-', label=y)
    fig.autofmt_xdate()
    plot.title(title)
    output = io.BytesIO()
    FigureCanvas(fig).print_png(output)
    return Response(output.getvalue(),mimetype='image/png',
        headers = {"Content-disposition":"attachment; filename=graph.png"})
    
    
