{
	"info": {
		"_postman_id": "cafaf887-99aa-4fc1-bae2-d2791635a1a6",
		"name": "Authentication",
		"schema": "https://schema.getpostman.com/json/collection/v2.0.0/collection.json",
		"_exporter_id": "4938209",
		"_collection_link": "https://cloudy-robot-50496.postman.co/workspace/Solusi-Mitra-Pertama~bfaa1ca3-466a-48a2-81fa-3490a66d8a62/collection/4938209-cafaf887-99aa-4fc1-bae2-d2791635a1a6?action=share&source=collection_link&creator=4938209"
	},
	"item": [
		{
			"name": "Register",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\"name\":\"Dedy Ibrahim\",\"email\":\"dedy@gmail.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": "http://localhost:8000/api/register"
			},
			"response": []
		},
		{
			"name": "Login",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\r\n    \"email\" :\"dedy@gmail.com\",\r\n    \"password\" :\"password123\"\r\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": "http://localhost:8000/api/login"
			},
			"response": []
		},
		{
			"name": "LogOut",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": {
						"token": "1|lnkrRFEEUO8M04YIoRHPN706v7vCTykB75U6XsHDb069ba55"
					}
				},
				"method": "POST",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\r\n    \"email\" :\"dedy@gmail.com\",\r\n    \"password\" :\"password123\"\r\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": "http://localhost:8000/api/logout"
			},
			"response": []
		},
		{
			"name": "verifyOTP",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\"token\":\"929081\",\"email\":\"dedy@gmail.com\"}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": "http://localhost:8000/api/2fa-authenticate"
			},
			"response": []
		}
	]
}
