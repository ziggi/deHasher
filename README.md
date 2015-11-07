# About deHasher
PHP class and web interface for dehashing hash sums.

# Demo
See demo here: http://hash.ziggi.org/

# Using external API
## Get hash
##### Example
```
http://hash.ziggi.org/api/hash.get?text=TEXT&type=TYPE
```
##### Parameters
Parameter | Optional | Description
--------- | -------  | ------------
text      | no       | for hashing
type      | yes      | type of hash
##### Result
If **type** is set then plain text. If not then json array with all hashes.

## Get dehashed text
##### Example
```
http://hash.ziggi.org/api/dehash.get?hash=HASH&type=TYPE&include_external_db
```
##### Parameters
Parameter           | Optional | Description
------------------- | -------  | ------------
hash                | no       | hash for dehashing
type                | yes      | type of hash
include_external_db | yes      | use external databases
##### Result
If result has been found then plain text. If not then nothin.

## Get count
##### Example
```
http://hash.ziggi.org/api/info.get?count&type=TYPE
```
##### Parameters
Parameter | Optional | Description
--------- | -------  | ------------
type      | yes      | type of hash
##### Result
If **type** is set then count of hashes with this type. If not then count of all hashes.

## Get supported algorythms
##### Example
```
http://hash.ziggi.org/api/info.get?algo&type=TYPE
```
##### Parameters
Parameter | Optional | Description
--------- | -------  | ------------
type      | yes      | type of hash
##### Result
If **type** is set then 1 or 0 depending on the availability of the algorithm. If not then JSON list of all available algorythms.
