# test accessing secret variables from here
import sys
import requests
import json

subdomain = sys.argv[1]
api_username = sys.argv[2]
api_token = sys.argv[3]

data = {
    "hostname": subdomain,
    "type": "A",
    "content": "43.252.36.172",
    "ttl": "300",
    "priority": "10"
}

headers = {
    "Api-Username": api_username,
    "Api-Token": api_token,
    "Content-Type": "application/json"
}

r = requests.post("https://api.name.com/api/dns/create/myshamra.com", data = json.dumps(data), headers = headers)

result = r.json()

if result["result"]["code"] == 251:
    print("Authentication error, please contact your sysadmin now")
    sys.exit(1)
