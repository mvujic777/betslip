# betslip
Welcome to my first project on Githhub 


Project description

The project is based on the principle of the sportbook. Players do not register and do not make a deposit, but bet on ready-made slips drawn up by the administrator. The players who hit the most pairs are ranked and rewarded.

Client side

There are tickets on the home page. The tickets are determined by the dates from when to when they are active. The last day of activity should be when the last game starts. After the ticket expires, the ticket is no longer visible on the home page. Inside each ticket there are pairs with an offer, i.e. the outcomes of the match. The client cannot choose which match to bet on, but bet on the outcome of the matches chosen by the administrator. The client does not register, but chooses the results, enters the username, email and address of the faucetpay wallet. After sending the bet, it awaits the end of the match and the administrator who will mark the correct results.
(Currently added slips take a very long time for users to see without adding new ones)

You can check the results in the top menu by clicking on Result - Tickets or Result - Leaderboard.

In the first menu, you can check results per ticket. Player gets 10 points for each hit pair. The one who played first is on top. On the same page, there is also a list of players and their statistics - which matches were bet on, the player's forecast and exact outcome, the total number of forecasts, the number of hits, misses and actives. The number of bets per sport is shown graphically.
In second menu is Leaderboard. The leaderboard is determined by dates, initial and final. The leadeerboard contains all ticket results from the date range in which it was determined. Based on the results of tickers, the results of the players are claculated. The player with the most points is at the top.

Admin side

The administrator adds sports on the Sports page. On the Betname page, it adds bet names such as full match, corners, double mistakes. On the Betvalue page it adds bet values like 1, X, 2, Over 8, Under 8, 8. On the Game page it adds the names of the matches with the start date eg Arsenal – Manchester City. By clicking on Update, you can correct the name of the match and add the end time of the match. On the Insert game details page, you link the match with the sport, bet name and bet values. On the same page, by clicking on Update at the end of the match, you indicate the winning outcome. If you wrongly linked a match to a sport or bet, delete the match by clicking Delete and link it again.

On the Create ticket page, you create a ticket specifying the start and end date. The ticket appears in the list and by clicking on the details you add matches to the ticket. If you added the wrong match, delete the match and add it again. The slip is automatically displayed on the home page.

On the Leaderboard page, you create rankings by specifying a start and end date. The leaderboard collects the results of all matches from the given time range. Clicking on Details displays the current list of users sorted by points.

For the purpose of testing the site, feel free to add matches, results and users.
Login for admin

admin:admin
