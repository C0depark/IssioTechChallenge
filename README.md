# Issio Tech Challenge

This repo contains a working solution for the Isso Tech Challenge.

Run on the console
```shell
php ChessChallenge.php
```

Example output
```shell
     --a----b----c----d----e----f----g----h--
--1--[   ][   ][   ][   ][   ][   ][   ][   ]
--2--[   ][   ][   ][   ][   ][   ][   ][   ]
--3--[   ][   ][B-w][   ][   ][   ][   ][   ]
--4--[   ][P-w][   ][   ][P-w][   ][   ][   ]
--5--[   ][   ][   ][   ][   ][   ][   ][   ]
--6--[   ][   ][   ][   ][R-b][P-b][   ][   ]
--7--[   ][   ][   ][   ][   ][   ][   ][   ]
--8--[   ][   ][   ][   ][   ][   ][   ][   ]
Checking move - From: [c,3]  To: [e,1] -- Success!
Checking move - From: [c,3]  To: [f,6] -- Success!
Checking move - From: [c,3]  To: [h,5] -- Fail!
Checking move - From: [c,3]  To: [b,4] -- Fail!
Checking move - From: [c,3]  To: [h,8] -- Fail!
Checking move - From: [e,6]  To: [a,6] -- Success!
Checking move - From: [e,6]  To: [e,4] -- Success!
Checking move - From: [e,6]  To: [c,5] -- Fail!
Checking move - From: [e,6]  To: [f,6] -- Fail!
Checking move - From: [e,6]  To: [e,1] -- Fail!
```