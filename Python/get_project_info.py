# -*- coding: utf-8 -*-
"""
Created on Fri Mar 19 07:22:09 2021

@author: Samsung
"""

import requests

#token = "150c6a3d-a5aa-440c-871c-ce81453c0a5d"

token = "d57f286b-dd50-3380-b509-1528a735f1f3"

processos = "./processos_pq1a/"


i = 1

with open("./processos_pq1a.tsv") as file:
    
    for line in file:

        numeroProcesso = line.strip().split("-")
        numeroProcesso_0 = numeroProcesso[0].split("/")
        
        #chaveProcesso = "20144653609"
        
        chaveProcesso = numeroProcesso_0[1] + numeroProcesso_0[0] + numeroProcesso[1]
        
        #url = "https://cnpqapi-fomento.cnpq.br/v1/lattesdata/processos/"
        url = "https://api.cnpq.br/lattes-data/v1/processos/"
        
        
        r = requests.get(url + chaveProcesso, headers={'Authorization': "Bearer " + token})
        
        print(i, chaveProcesso, r.status_code, r.text[:500])

        
        file0 =  open(processos + chaveProcesso + ".json", "wb")
        file0.write(r.content)
        file0.close()

        
        i += 1
