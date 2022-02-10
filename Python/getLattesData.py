import requests

url = "https://cnpqapi-fomento.cnpq.br/v1/lattesdata/processos/"
token = "b8fb20a6-15ed-40b1-87e1-a1da20a82c1b"

processos = "./processos_pq1a/"
chaveProcesso = "20144653609"

r = requests.get(url + chaveProcesso, headers={'auth-token':token})
  r = requests.get(url + chaveProcesso, headers={'auth-token': token})

print(i, chaveProcesso, r.status_code, r.text[:500])


file0 = open(processos + chaveProcesso + ".json", "wb")
file0.write(r.content)
file0.close()

pq1a2016

20143034985