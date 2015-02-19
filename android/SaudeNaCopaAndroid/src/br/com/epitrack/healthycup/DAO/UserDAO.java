package br.com.epitrack.healthycup.DAO;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.location.Location;
import android.location.LocationListener;
import android.os.Bundle;
import android.util.Log;
import br.com.epitrack.healthycup.classes.EventoCadastro;
import br.com.epitrack.healthycup.classes.EventoEsqueciSenha;
import br.com.epitrack.healthycup.classes.EventoLogin;
import br.com.epitrack.healthycup.classes.ItemCalendario;
import br.com.epitrack.healthycup.classes.Sentimento;
import br.com.epitrack.healthycup.classes.Usuario;

import com.google.gson.Gson;

public class UserDAO implements LocationListener {
	// private static String PATH_SERVIDOR =
	// "http://saudenacopa.epitrack.com.br/api/rest/";
	private static String PATH_SERVIDOR = "http://www.saudenacopa.com/api/rest/";
	private static final String TAG = "SaudeNaCopa";
	public static final long MIN_DISTANCE_CHANGE_FOR_UPDATES = 10; // 10 meters
	public static final long MIN_TIME_BW_UPDATES = 1000 * 60 * 1; // 1 minute

	private static String convertStreamToString(InputStream is) {
		/*
		 * To convert the InputStream to String we use the
		 * BufferedReader.readLine() method. We iterate until the BufferedReader
		 * return null which means there's no more data to read. Each line will
		 * appended to a StringBuilder and returned as String.
		 */
		BufferedReader reader = new BufferedReader(new InputStreamReader(is));
		StringBuilder sb = new StringBuilder();

		String line = null;
		try {
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
			}
		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			try {
				is.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return sb.toString();
	}

	/**
	 * Realiza login
	 * 
	 * @param login
	 * @param senha
	 * @return
	 */
	public EventoLogin realizaLogin(String login, String senha) {

		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "login");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("login", login));
		params.add(new BasicNameValuePair("senha", senha));
		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				// result =
				// "{'status': true,'usuario': {userID':234565362551 ,'nome': 'Lucas','sexo': 'masculino','pontos': 804,'engajamento': 94,'categoria': 4,'nivel': 2 }}";
				instream.close();
				Gson gson = new Gson();
				EventoLogin evento = new EventoLogin();
				try {
					evento = gson.fromJson(result, EventoLogin.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoEsqueciSenha esqueciSenha(String email) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "esqueciSenha");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("email", email));
		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				instream.close();
				Gson gson = new Gson();
				EventoEsqueciSenha evento = new EventoEsqueciSenha();
				try {
					evento = gson.fromJson(result, EventoEsqueciSenha.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoCadastro realizaCadastro(Usuario user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "cadastraUsuario");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("email", user.getEmail()));
		params.add(new BasicNameValuePair("apelido", user.getNome()));
		params.add(new BasicNameValuePair("idade", String.valueOf(user
				.getIdade())));
		params.add(new BasicNameValuePair("sexo", user.getSexo()));
		params.add(new BasicNameValuePair("senha", user.getSenha()));

		params.add(new BasicNameValuePair("idioma", user.getIdioma()));
		params.add(new BasicNameValuePair("gcmid", user.getGcmID()));
		params.add(new BasicNameValuePair("device", "android"));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				instream.close();
				Gson gson = new Gson();
				EventoCadastro evento = new EventoCadastro();
				try {
					evento = gson.fromJson(result, EventoCadastro.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoLogin enviaSentimento(Sentimento sentimento) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "sentimento");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("sentimento", String
				.valueOf(sentimento.getId())));
		params.add(new BasicNameValuePair("usuario_id", sentimento.getUser()
				.getUserID()));
		params.add(new BasicNameValuePair("latitude", sentimento.getLatitude()));
		params.add(new BasicNameValuePair("longitude", sentimento
				.getLongitude()));
		params.add(new BasicNameValuePair("campo1", String.valueOf(sentimento
				.getCampos()[0])));
		params.add(new BasicNameValuePair("campo2", String.valueOf(sentimento
				.getCampos()[1])));
		params.add(new BasicNameValuePair("campo3", String.valueOf(sentimento
				.getCampos()[2])));
		params.add(new BasicNameValuePair("campo4", String.valueOf(sentimento
				.getCampos()[3])));
		params.add(new BasicNameValuePair("campo5", String.valueOf(sentimento
				.getCampos()[4])));
		params.add(new BasicNameValuePair("campo6", String.valueOf(sentimento
				.getCampos()[5])));
		params.add(new BasicNameValuePair("campo7", String.valueOf(sentimento
				.getCampos()[6])));
		params.add(new BasicNameValuePair("campo8", String.valueOf(sentimento
				.getCampos()[7])));
		params.add(new BasicNameValuePair("campo9", String.valueOf(sentimento
				.getCampos()[8])));
		params.add(new BasicNameValuePair("campo10", String.valueOf(sentimento
				.getCampos()[9])));
		params.add(new BasicNameValuePair("campo11", String.valueOf(sentimento
				.getCampos()[10])));
		params.add(new BasicNameValuePair("campo12", String.valueOf(sentimento
				.getCampos()[11])));

		EventoLogin evento = new EventoLogin();
		HttpResponse response;
		Gson gson = new Gson();
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, "enviaSentimento: " + result);
				instream.close();
				// erro: {"status":false,"tempo":"12341451134","erro":5}
				JSONObject jObject;
				try {
					jObject = new JSONObject(result);
					boolean status = jObject.getBoolean("status");
					if (!status) {
						String tempo = jObject.getString("tempo");
						int erro = jObject.getInt("erro");
						Log.i(TAG,
								"tempo: " + tempo + " erro:"
										+ String.valueOf(erro));
						evento.setStatus(false);
						Usuario user = new Usuario();
						user.setNumHoraParaInformarSentimento(tempo);
						evento.setUsuario(user);
						return evento;
					}
				} catch (JSONException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}

				evento = new EventoLogin();
				try {
					evento = gson.fromJson(result, EventoLogin.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;

	}

	public static Location lastBestLocation = null;

	// public void atualizaLocalizacao(Activity mActivity) {
	// LocationManager Locationm = (LocationManager)
	// mActivity.getSystemService(Context.LOCATION_SERVICE);
	// Criteria crit = new Criteria();
	// crit.setAccuracy(Criteria.ACCURACY_LOW);
	// crit.setPowerRequirement(Criteria.POWER_LOW);
	//
	// String bestProvider = Locationm.getBestProvider(crit, true);
	// if (bestProvider == null) {
	// Locationm.requestLocationUpdates(LocationManager.GPS_PROVIDER,
	// MIN_TIME_BW_UPDATES, MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
	// Locationm.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,
	// MIN_TIME_BW_UPDATES, MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
	// } else {
	// Location l = Locationm.getLastKnownLocation(bestProvider);
	// if (l != null) {
	// Log.i(TAG, "Lat: " + String.valueOf(l.getLatitude()));
	// Log.i(TAG, "Long: " + String.valueOf(l.getLongitude()));
	// } else {
	// // --GPS
	// Locationm.requestLocationUpdates(LocationManager.GPS_PROVIDER,
	// MIN_TIME_BW_UPDATES, MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
	//
	// // Network provider
	// try {
	// Locationm.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,
	// MIN_TIME_BW_UPDATES, MIN_DISTANCE_CHANGE_FOR_UPDATES, this);
	// } catch (Exception e) {
	// Log.e(TAG, e.getMessage());
	// }
	//
	// }
	// }
	//
	// }

	@Override
	public void onLocationChanged(Location location) {
		try {

			Log.i(TAG, "Lat: " + String.valueOf(location.getLatitude()));
			Log.i(TAG, "Long: " + String.valueOf(location.getLongitude()));

		} catch (Exception e) {
			// TODO: handle exception
		}

	}

	@Override
	public void onProviderDisabled(String provider) {

	}

	@Override
	public void onProviderEnabled(String provider) {

	}

	@Override
	public void onStatusChanged(String provider, int status, Bundle extras) {

	}

	public EventoCadastro enviaDuvida(String duvida, Usuario user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "duvida");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("idusuario", String.valueOf(user
				.getUserID())));
		params.add(new BasicNameValuePair("msg", duvida));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				instream.close();
				Gson gson = new Gson();
				EventoCadastro evento = new EventoCadastro();
				try {
					evento = gson.fromJson(result, EventoCadastro.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoEsqueciSenha atualizaGCMID(String gcmId, String user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "updateGcm");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("usuario_id", user));
		params.add(new BasicNameValuePair("gcmid", gcmId));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				instream.close();
				Gson gson = new Gson();
				EventoEsqueciSenha evento = new EventoCadastro();
				try {
					evento = gson.fromJson(result, EventoEsqueciSenha.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoCadastro salvaArenaAtiva(int arena, Usuario user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "updateUserArena");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("usuario_id", user.getUserID()));
		params.add(new BasicNameValuePair("arena", String.valueOf(arena)));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				instream.close();
				Gson gson = new Gson();
				EventoCadastro evento = new EventoCadastro();
				try {
					evento = gson.fromJson(result, EventoCadastro.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public EventoCadastro trocaSenha(String senhaAtual, String senha,
			Usuario user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "alterarSenha");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(3);
		params.add(new BasicNameValuePair("userID", user.getUserID()));
		params.add(new BasicNameValuePair("senhaAtual", senhaAtual));
		params.add(new BasicNameValuePair("novaSenha", senha));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				Log.i(TAG, result);
				instream.close();
				Gson gson = new Gson();
				EventoCadastro evento = new EventoCadastro();
				try {
					evento = gson.fromJson(result, EventoCadastro.class);
				} catch (Exception e) {
					evento.setStatus(false);
				}

				return evento;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
		}
		return null;
	}

	public String buscaConsulados() {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(
				"http://www.gamfig.com/saudenacopa/consulados.php");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(3);
		params.add(new BasicNameValuePair("userID", ""));
		params.add(new BasicNameValuePair("senhaAtual", ""));
		params.add(new BasicNameValuePair("novaSenha", ""));

		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream =  entity.getContent();
				String result = convertStreamToString(instream);
				result = URLDecoder.decode(result, "UTF-8"); 
				//Log.i(TAG, result);
				instream.close();

				return result;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public String buscaHospitaisReferencia() {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(
				"http://www.gamfig.com/saudenacopa/hosp_ref.php");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(0);
		
		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
				new URLDecoder();
				result = URLDecoder.decode(result, "UTF-8"); 
				//Log.i(TAG, result);
				instream.close();

				return result;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
			e.printStackTrace();
		}
		return null;
	}

	public List<ItemCalendario> buscarCalendario(Usuario user) {
		HttpClient httpclient = new DefaultHttpClient();

		HttpPost httppost = new HttpPost(PATH_SERVIDOR + "calendario");

		// Request parameters and other properties.
		List<NameValuePair> params = new ArrayList<NameValuePair>(1);
		params.add(new BasicNameValuePair("idusuario", user.getUserID()));
//		Log.i(TAG, user.getUserID());
		HttpResponse response;
		try {
			httppost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
			response = httpclient.execute(httppost);
			// Log.i(TAG,response.getStatusLine().toString());
			HttpEntity entity = response.getEntity();
			if (entity != null) {
				InputStream instream = entity.getContent();
				String result = convertStreamToString(instream);
//				Log.i(TAG, result);
				instream.close();
				List<ItemCalendario> itensCalendario = new ArrayList<ItemCalendario>();
				try {
					JSONObject obj = new JSONObject(result);
//					JSONObject retorno = obj.getJSONObject("retorno");
					JSONArray datas = obj.getJSONArray("data");
					for (int i = 0; i < datas.length(); i++) {
						JSONObject data = datas.getJSONObject(i);
						ItemCalendario item = new ItemCalendario();
						item.setData(data.getString("dataCadastro"));
						item.setSentimento(data.getDouble("sentimento"));
						itensCalendario.add(item);
					}
				} catch (JSONException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				
				
				try {
					
				} catch (Exception e) {
					
				}

				return itensCalendario;
			}
		} catch (ClientProtocolException e) {
		} catch (IOException e) {

			e.printStackTrace();
		}
		return null;
	}

	

}
