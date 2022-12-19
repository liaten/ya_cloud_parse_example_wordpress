from json import loads, dump
from api_test import translate
from time import sleep

# читаем файл c json-ами
fr = open('nyt2.json', "r", encoding="utf-8").readlines()

# fw = open('nyt2_ru.json', "a", encoding="utf-8")

# читаем файл с кол-вом обновленных линий
lines_updated = int(open('lines_updated.txt', "r", encoding="utf-8").read())
# print('lines updated:\n')
# for page in range(0,len(lines)):
for page in range(lines_updated,len(fr)):
    # переводим построчно в json-объект
    data = loads(fr[page])
    # print(data)
    # создаем массив с данными к переводу
    data_to_translate = []
    # ищем данные к переводу в json и записываем их в конец массива
    for i in data:
        if i in ['author','description','publisher','title']:
            data_to_translate.append(data[i])
    # переводим
    translated_data = translate(data_to_translate)
    # обновляем счетчик
    lines_updated +=1
    # print(lines_updated, translated_data)
    # переведенные данные переводим в json
    translated_json = loads(translated_data)
    data_to_out = []
    for data_tr in translated_json['translations']:
        try:
            data_to_out.append(data_tr['text'])
        except KeyError:
            data_to_out.append("")
    data['author'], data['description'], data['publisher'], data['title'] = data_to_out[0], data_to_out[1], data_to_out[2], data_to_out[3]

    # записывать будем сюда
    with open('nyt2_ru.json', "a", encoding="utf-8") as lines_write:
        dump(data,lines_write)
        lines_write.write('\n')
    # lines_write = open('nyt2_ru.json', "a", encoding="utf-8")
    # dump(data,fw)
    # fw.write('\n')

    # if(lines_updated%10==0):
    #     print(lines_updated)
        
    
    # читаем файл с кол-вом обновленных линий
    lines_updated_out = open('lines_updated.txt', "w", encoding="utf-8").write(str(lines_updated))
