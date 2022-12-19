from requests import post

def translate(text):
	IAM_TOKEN = 't1.9euelZqems2Mk56ekMaKjo_Oi5CSj-3rnpWancqUkYnJyMaYkJbIio7Gj5Ll8_dMP1tk-e8WNmJ5_N3z9wxuWGT57xY2Ynn8.gqNrcdRFgxz0-0WLtR4n8wZ1_KaS1U-A0hmzcapxYE-LnW7cX61MsltSy7CZeSwFW-_B8HFxD5vkgXoIhEzYDQ'
	folder_id = 'b1gb6lborq1om69aif63'
	target_language = 'ru'
	texts = [text]

	body = {
		"targetLanguageCode": target_language,
		"texts": texts,
		"folderId": folder_id,
	}

	headers = {
		"Content-Type": "application/json",
		"Authorization": "Bearer {0}".format(IAM_TOKEN)
	}

	response = post('https://translate.api.cloud.yandex.net/translate/v2/translate',
		json = body,
		headers = headers
	)

	return response.text # as json