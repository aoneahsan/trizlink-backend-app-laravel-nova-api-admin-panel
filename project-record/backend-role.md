- when boss say I backend, means api, nova includes on it.

- validation flow in controller file
  - first check permissions
  - then check request data
  - then check the user/workspace/team/post/shortlink/etc in db (for 404 error)
  - once everything is there now continue with the request.
