ENetMF

This repository contains all the files necessary for replicating the results presented in the paper "An Elastic Net Regularized Matrix Factorization Technique for Recommender Systems", by Bianca Mitroi and Flavius Frasincar.

The dataset used for this research is the MovieLens 100K dataset, available at https://grouplens.org/datasets/movielens/100k/
The full MovieLens 100K dataset contains 100,000 ratings (1-5) given by 943 users on 1,682 items. The dataset is a tab separated list of <user id | item id | rating | timestamp>. The time stamps are unix seconds since 1/1/1970 UTC.

This research performs 5-fold cross-validation, whereby the full dataset is split into 80%/20% training and test sets, as follows - the datasets taken as input by the algorithm are: u1.base, u1.test, u2.base, u2.test, u3.base, u3.test, u4.base, u4.test, u5.base, u5.test -- The datasets u1.base and u1.test through u5.base and u5.test are 80%/20% splits of the MovieLens 100K dataset into training and test data. Each of u1, ..., u5 have disjoint test sets, used for 5-fold cross-validation (where you repeat your experiment with each training and test set and average the results). The 10 datasets are included in the zipped file ml-100k.zip available at https://grouplens.org/datasets/movielens/100k/

In order to run the program, you need to download the 10 datasets (u1.base and u1.test through u5.base and u5.test) and save them into a folder called "import_data". The "import_data" folder needs to be created in the same directory as the other three folders ("config", "inc", and "results") provided here.
