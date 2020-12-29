from pymongo import MongoClient

from flask import current_app,g

def get_db():
    if 'db' not in g:
        g.dbclient = MongoClient(current_app.config['DATABASE_URI'])
        g.db = g.dbclient.stockSite
    return g.db

def close_db():
    client = g.pop('dbclient', None)
    db = g.pop('db', None)

    if client is not None:
        client.close()
