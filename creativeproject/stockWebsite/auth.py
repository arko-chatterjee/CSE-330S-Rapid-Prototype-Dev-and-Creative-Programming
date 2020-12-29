import functools, re
from flask import (
    Blueprint, flash, g, redirect, render_template, request, session, url_for
)
from werkzeug.security import check_password_hash, generate_password_hash
from stockWebsite.db import get_db
from bson.objectid import ObjectId
import secrets

bp = Blueprint('auth',__name__,url_prefix='/auth')

@bp.route('/register',methods=('GET','POST'))
def register():
    if request.method == 'POST':
        username = str(request.form['username'])
        password = str(request.form['password'])
        db = get_db()
        error = None
        loginReg = re.compile(r'^[A-Za-z0-9_\@\.\&\-]*$')
        if not username:
            error = "You must enter a username"
        elif not re.match(loginReg, username) or len(username)<2:
            error='Invalid Username'
        elif not password:
            error = 'You must enter a password'
        elif not re.match(loginReg, password) or len(password)<2:
            error = 'Invalid Password'
        elif db.users.find_one({"username":username}) is not None:
            error = f'User {username} already exists'

        if error is None:
            newUser = {
                'username':username,
                'hashedPass':generate_password_hash(password),
                'stockList':{}
            }
            db.users.insert_one(newUser)
            return redirect(url_for('auth.login'))
        flash(error)
    return render_template('auth/register.html')

@bp.route('/login',methods=('GET','POST'))
def login():
    if request.method == 'POST':
        username = str(request.form['username'])
        password = str(request.form['password'])
        db = get_db()
        error = None
        userFromDb = db.users.find_one({'username':username})

        if userFromDb is None:
            error = 'Incorrect Username'
        elif not check_password_hash(userFromDb['hashedPass'],password):
            error = 'Incorrect Password'

        if error is None:
            session.clear()
            session['user_id'] = str(userFromDb['_id'])
            session['token'] = str(secrets.token_hex(32))
            return redirect(url_for('index'))
        
        flash(error)
    return render_template('auth/login.html')

@bp.before_app_request
def load_current_user():
    user_id = session.get('user_id')
    g.token = session.get('token')
    if user_id is None:
        g.user = None
    else:
        g.user = get_db().users.find_one({'_id': ObjectId(user_id)})

@bp.route('/logout')
def logout():
    session.clear()
    return redirect(url_for("index"))

def login_required(view):
    @functools.wraps(view)
    def wrapped_view(**kwargs):
        if g.user is None:
            return redirect(url_for('auth.login'))

        return view(**kwargs)

    return wrapped_view