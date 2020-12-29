from flask import Flask

app = Flask(__name__, instance_relative_config=True)
app.config.from_object('config')
app.config.from_pyfile('config.py')

@app.route('/hello')
def hello():
    return "Hello, World!"

from . import screen
app.register_blueprint(screen.bp)
app.add_url_rule('/', endpoint='index')

from . import auth
app.register_blueprint(auth.bp)

from . import profile
app.register_blueprint(profile.bp)

from . import graph
app.register_blueprint(graph.bp)
