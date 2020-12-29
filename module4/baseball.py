import re, sys, os

class Player:
    def __init__(self, name):
        self.name = name
        self.bats = 0
        self.hits = 0
        self.runs = 0
        self.average = 0

    def addBat(self, bats):
        self.bats+=bats
    
    def addHit(self, hits):
        self.hits+=hits

def main(argv):
    if len(sys.argv) < 2:
        sys.exit(f"Usage: {sys.argv[0]} filename")

    filename = sys.argv[1]

    if not os.path.exists(filename):
        sys.exit(f"Error: File '{sys.argv[1]}' not found")

    overall_regex = re.compile(r"(^.*)\sbatted\s(\d)\stimes\swith\s(\d)\shits\sand\s(\d)")

    players = {}

    with open (filename) as f:
        for line in f:
            match_check = re.match(overall_regex,line);
            if match_check:
                name = match_check.group(1)
                numBat = int(match_check.group(2))
                numHit = int(match_check.group(3))
                numRun = int(match_check.group(4))
                if name in players:
                    players[name].addBat(numBat)
                    players[name].addHit(numHit)
                else:
                    players[name] = Player(name)
                    players[name].addBat(numBat)
                    players[name].addHit(numHit)

    playersSorted = sorted(players.items(),key=lambda item:(item[1].hits/item[1].bats), reverse=True)
    for p in playersSorted:
        print(f'{p[0]}: {round(p[1].hits/p[1].bats,3):.3f}')

if __name__ == "__main__":
    main(sys.argv)