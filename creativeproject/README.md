# Final Project
For this final project, I was required to learn new technologies and frameworks of my choice to build a website that accomplishes some goal I set.  
The two frameworks/technologies I learned/used in this project were:
- Flask
- MongoDB

I used these along with HTML/CSS/Javascript for the front-end of the website to create a stock screening website. I added the functionality to screen for stocks based on various criterion like market cap and volume. Site visitors have the option to register with the site and create an account, with which they can then view their own profile. In their profiles, they can create and view watchlists that they've created, and the watchlists can store bookmarks with relevant information for a pertaining stock. Users also had the ability to download search or specific stock fundamentals as a csv, or to view the price history of a stock in a graph.

To run this on your local machine, clone the repository to a directory of your choosing on your local machine.  
Inside that directory, create your python virtual environment with  
 `$ python3 -m venv venv`  
Then you can activate the virtual environment on Windows by running  
 `$ venv\Scripts\activate` or on Linux through `$ . venv/bin/activate`  
Once this has all been done, you can install the required python packages by running   
`$ pip install -r /path/to/requirements.txt`  
Now you're almost there, now in config.py you need to populate the MongoDB database URI (for logging in/out) and your AlphaVantage API key (for graphs)  
Now just type `$ python3 run.py` and open localhost:5000 in your browser and the website should be working