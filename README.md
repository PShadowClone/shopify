
# Instructions

- Create a new env.ini file at the root of project
- Set these attributes
```
[ACCOUNT]
API_KEY = "21a33b6a61b8a693085fc56b1d928042"
API_TOKEN = "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"
STORE_URL = "7263bc-fd.myshopify.com"
VERIFY_SSL = 0
```


## Reference

#### Save products in the given store

```http
  GET {project_url}/store
```
#### Fetch all products, sensitize them and update their quantity

```http
  GET {project_url}/
```
This page performs the following points:
- Fetches all products from the store
- Remove attributes that have null, empty, or N\A values
- Update Inventory quantity
- You can Find all fetched and updated products in the ```storage/fetched_products.json```



## Author

- [Amr Saidam](amr.saidam.94@gmail.com)

