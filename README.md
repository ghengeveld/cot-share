# Share

Share is a generic API for node sharing. It allows owners to share their items 
with other users, by modifying user rights on a per-item basis. The API 
interface is very similar to cot_auth().

Example nodes:

* Pages
* PFS Files
* Forum topics

## Features

* Set sharing permissions on a node for all users (fallback value)
* Set sharing permissions on a node for a specific user
* Get sharing permissions on a node for all users (fallback value)
* Get sharing permissions on a node for a specific user
* Get sharing permissions on a node for the current user ($usr)