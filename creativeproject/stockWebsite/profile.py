from werkzeug.utils import redirect
from stockWebsite.auth import login_required
from hmac import compare_digest
from pymongo import ReturnDocument
from flask import(
    Blueprint, g, render_template, request, session, url_for, flash
)

bp = Blueprint('profile',__name__,url_prefix='/profile')

@bp.route('/')
@login_required
def profile():
    user = g.user
    stockLists = user['stockList']
    username = g.user['username']
    return render_template('profile/profile.html', list=stockLists, name=username)

@bp.route('/clist', methods=('GET','POST'))
@login_required
def cList():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        users = g.db.users
        currentUser = g.user
        lists = currentUser['stockList']
        newlistname = request.form.get('listname')
        if newlistname not in lists:
            lists[newlistname] = {}
            g.user = users.find_one_and_update(
                {"_id":currentUser['_id'], "username":currentUser['username']},
                {'$set': {'stockList':lists}},
                return_document=ReturnDocument.AFTER
            )
        return redirect(url_for('profile.profile'))

@bp.route('/dlist', methods=('GET', 'POST'))
@login_required
def dList():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        users = g.db.users
        currentUser = g.user
        lists = currentUser['stockList']
        dellistname = request.form.get('listname')
        del lists[dellistname]
        g.user = users.find_one_and_update(
            {'_id':currentUser['_id'], 'username' : currentUser['username']},
            {'$set' : {'stockList' : lists}},
            return_document=ReturnDocument.AFTER
        )
        return redirect(url_for('profile.profile'))

@bp.route('/add/intermediate', methods=('GET','POST'))
@login_required
def addintermediate():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        lists = g.user['stockList']
        ticker = request.form.get('ticker')
        return render_template('profile/addInt.html', lists=lists, ticker=ticker)

@bp.route('/add',methods=('GET','POST'))
@login_required
def addtolist():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        lists=g.user['stockList']
        users=g.db.users
        currentUser = g.user
        ticker = request.form.get('ticker')
        listname = request.form.get('listname')
        listEdit = lists[listname]
        if ticker not in listEdit.keys():
            listEdit[ticker] = []
            lists[listname] = listEdit
        g.user = users.find_one_and_update(
            {'_id':currentUser['_id'], 'username' : currentUser['username']},
            {'$set' : {'stockList' : lists}},
            return_document=ReturnDocument.AFTER
        )
        return redirect(url_for('profile.profile'))

@bp.route('/remove',methods=('GET','POST'))
@login_required
def removefromlist():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        lists=g.user['stockList']
        users=g.db.users
        currentUser = g.user
        ticker = request.form.get('ticker')
        listname = request.form.get('listname')
        listEdit = lists[listname]
        if ticker in listEdit.keys():
            del listEdit[ticker]
            lists[listname] = listEdit
        g.user = users.find_one_and_update(
            {'_id':currentUser['_id'], 'username' : currentUser['username']},
            {'$set' : {'stockList' : lists}},
            return_document=ReturnDocument.AFTER
        )
        return redirect(url_for('profile.profile'))

@bp.route('/addbookmark',methods=('GET','POST'))
@login_required
def addbookmark():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        error = None
        lists=g.user['stockList']
        users=g.db.users
        currentUser = g.user
        ticker = request.form.get('ticker')
        link = request.form.get('link')
        listname = request.form.get('listname')
        stocklist = lists[listname]
        stocklist[ticker].append(link)
        lists[listname]=stocklist
        if len(link) > 5:
            g.user = users.find_one_and_update(
                {'_id':currentUser['_id'], 'username' : currentUser['username']},
                {'$set' : {'stockList' : lists}},
                return_document=ReturnDocument.AFTER
            )
        else:
            error = 'Link too short'
        if error is not None:
            flash(error)
        return redirect(url_for('profile.profile'))

@bp.route('/delbookmark',methods=('GET','POST'))
@login_required
def delbookmark():
    if request.method == 'POST':
        if not compare_digest(request.form.get('token'), session.get('token')): #token validation
            raise NameError('Session Hijack Detected')
        lists=g.user['stockList']
        users=g.db.users
        currentUser = g.user
        link = request.form.get('bookmarks')
        ticker = request.form.get('ticker')
        listname = request.form.get('listname')
        stocklist = lists[listname]
        stocklist[ticker].remove(link)
        lists[listname]=stocklist
        g.user = users.find_one_and_update(
            {'_id':currentUser['_id'], 'username' : currentUser['username']},
            {'$set' : {'stockList' : lists}},
            return_document=ReturnDocument.AFTER
        )
        return redirect(url_for('profile.profile'))