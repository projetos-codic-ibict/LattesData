import json
import requests
import textwrap
import time


def get_json(url):
    """Implementao em Python de getJSON do arquivo VerificaAPI.htm"""
    resp = requests.get(url)
    return resp.json()


def imprimir_dados(json_dict):
    """Converte um dicionário de dados JSON em texto legível."""
    # Traduz o dicionário JSON para uma string legível.
    json_string = json.dumps(json_dict, indent=4, ensure_ascii=False, sort_keys=True)

    # Remove caracteres do JSON para tornar o texto mais natural.
    for c in ['"', ',', '{', '}', '[', ']']:
        json_string = json_string.replace(c, '')

    # Remove tabulação excessiva
    json_string = textwrap.dedent(json_string).strip()

    # Adiciona separador visual para facilitar leitura.
    return json_string.replace('\n\n\n', '\n\n{}\n\n'.format('#'*40))


def verifica_api():
    """Implementacao em Python de VerificaAPI.htm"""
    processo = input('Entre com o número do processo no formato dddddd/aaaa-v: ')

    if len(processo) == 13:
        digitos_esquerda, digitos_direita_e_dv = processo.split('/', maxsplit=1)
        digitos_direita, dv = digitos_direita_e_dv.split('-', maxsplit=1)

        Dig = digitos_esquerda+digitos_direita
        DV = int(dv)

        DVC = 11 - sum([int(d)*m for d, m in zip(Dig,
                                                 [9, 8, 7, 6, 5, 4, 0, 0, 3, 2])]) % 11

        if DVC == 10 or DVC == 11:
            DVC = 0

        Ano = Dig[6]+Dig[7]

        if ((DV != DVC) or (Ano != '20' and Ano != '19')):
            print('\nNúmero de processo {} inválido: DV calculado = {}\n'.format(processo, DVC))
        else:
            print('\nNúmero de processo {} válido: DV calculado = {}\n'.format(processo, DVC))

            query_url = 'https://jsonplaceholder.typicode.com/users'
            print('Entrando em contato com {} para recuperar informações.'.format(query_url))
            query_start = time.time()
            json_dict = get_json(query_url)
            print('Conexão com {} durou {} segundos.\n'.format(query_url, time.time()-query_start))

            # Traduz o dicionário JSON para uma string legível.
            json_string = imprimir_dados(json_dict)

            print('Conteúdo recuperado do processo {}:\n\n{}'.format(processo, json_string))
            processo_txt = '_'.join(['processo', digitos_esquerda, digitos_direita, dv])+'.txt'
            with open(processo_txt, 'w') as f:
                f.write(json_string)
            print('\nConteúdo gravado no arquivo {}'.format(processo_txt))

            return json_dict
    else:
        print('\nO processo tem que ter 13 caracteres no formato dddddd/dddd-d')

    return None


if __name__ == '__main__':
    verifica_api()
