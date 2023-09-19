# General Coding Rules

- function, variable etc naming rules
  - we will use camel case
  - should start lowercase
- PLEASE DO NOT ADD HARD CODED VALUES "name, email, password, etc"
  - create a config file "zlinkAppData" and add all such data in that file so we will be able to change it easy when going to production
    - make it so that it checks whether we are in local or production mode and get data from "local" object or "production" object depending on that setting.
