package br.com.epitrack.healthycup;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentManager.OnBackStackChangedListener;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.ContextThemeWrapper;
import android.view.GestureDetector;
import android.view.Gravity;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.Window;
import android.view.animation.Animation;
import android.view.animation.LinearInterpolator;
import android.view.animation.TranslateAnimation;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;
import br.com.epitrack.healthycup.DAO.ArenaDAO;
import br.com.epitrack.healthycup.DAO.EnviarSentimento;
import br.com.epitrack.healthycup.DAO.SaudeDAO;
import br.com.epitrack.healthycup.DAO.UserDAO;
import br.com.epitrack.healthycup.adapters.ImageAdapter;
import br.com.epitrack.healthycup.adapters.SintomasAdapter;
import br.com.epitrack.healthycup.adapters.TweetAdapter;
import br.com.epitrack.healthycup.classes.Arena;
import br.com.epitrack.healthycup.classes.EventoCadastro;
import br.com.epitrack.healthycup.classes.ItemCalendario;
import br.com.epitrack.healthycup.classes.Sentimento;
import br.com.epitrack.healthycup.classes.Tweet;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.fragments.maisopcoes.AlterarSenhaFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.ArenasFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.CalendarioJogosFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.DenunciarFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.EmbaixadasFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.ItensJogo;
import br.com.epitrack.healthycup.fragments.maisopcoes.LinksFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.SobreTermosFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.VersaoFragment;
import br.com.epitrack.healthycup.fragments.maisopcoes.WebviewFragment;
import br.com.epitrack.healthycup.fragments.saude.CategoriaFragment;
import br.com.epitrack.healthycup.fragments.saude.MapaFragment;
import br.com.epitrack.healthycup.fragments.saude.TelefonesFragment;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

import com.google.analytics.tracking.android.EasyTracker;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.roomorama.caldroid.CaldroidFragment;

@SuppressLint("SimpleDateFormat")
public class SentimentoActivity extends ActionBarActivity implements LocationListener {

	Usuario user;
	Bundle extras;
	public static final String TAG = "SaudeNaCopa";
	private static final long FIVE_MINS = 5 * 60 * 1000;

	private CaldroidFragment caldroidFragment;

	private ViewFlipper mFlipper;
	private GestureDetector mGestureDetector;
	private List<Arena> arenas;
	private int arenaAtiva;
	private int fragmentAtivo;
	private PreferenciasUtil prefUtil;

	// Reference to the LocationManager
	private LocationManager mLocationManager;

	// The last valid location reading
	private Location mLastLocationReading;

	// default minimum time between new location readings
	private long mMinTime = 50000;

	// default minimum distance between old and new readings.
	private float mMinDistance = 1000.0f;

	private void setCustomResourceForDates() {

		SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
		List<ItemCalendario> itens = new PreferenciasUtil().getCalendario(getApplicationContext());
		if (itens != null) {

			try {
				for (ItemCalendario item : itens) {
					Date date = formatter.parse(item.getData());
					int cor = 0;
					if (caldroidFragment != null) {
						if (item.getSentimento() > 3) {
							cor = R.color.muitomal_vermelho;
						} else {
							if (item.getSentimento() > 2) {
								cor = R.color.mal_vermelho;
							} else {
								if (item.getSentimento() > 1) {
									cor = R.color.bem_laranja;
								} else {
									cor = R.color.muitobem_amarelo;
								}
							}

						}
						caldroidFragment.setBackgroundResourceForDate(cor, date);
						caldroidFragment.setTextColorForDate(R.color.white, date);
					}
				}

			} catch (ParseException e) {
				e.printStackTrace();
			}
		}
	}


	 @Override
	  public void onStart() {
	    super.onStart();
	    EasyTracker.getInstance(this).activityStart(this);
	  }

	  @Override
	  public void onStop() {
	    super.onStop();
	    EasyTracker.getInstance(this).activityStop(this);
	  }


	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.activity_sentimento);

		prefUtil = new PreferenciasUtil();

		// busca as infos passadas
		extras = getIntent().getExtras();
		Gson gson = new Gson();

		user = gson.fromJson(extras.getString("usuario"), Usuario.class);
		new PreferenciasUtil().salvaUser(user, getApplicationContext());

		// busca o calendario
		// verificar se tem internet

		if (new PreferenciasUtil(getApplicationContext()).isOnline()) {
			new BuscarCalendario(user).execute();
		}

		// arena ativa user
		arenaAtiva = user.getArena();

		arenas = getArenas(new Sentimento().calcularQuantidadeArena(user.getEngajamento()));
		// salvar numero de arenas
		new PreferenciasUtil().salvaNumeroArenas(arenas.size(), this);
		Log.i(TAG, "arenas:" + arenas.size());

		if (savedInstanceState == null) {
			// getActionBar().setTitle(R.string.jogador);

			atualizaTitulo(R.string.jogador, R.drawable.topo_jogador);
			SentimentoFragment sentimentoFrag = new SentimentoFragment();
			sentimentoFrag.setArguments(extras);
			FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.add(R.id.container, sentimentoFrag);
			fragmentTransaction.addToBackStack("Sentimento");
			fragmentTransaction.commit();
			fragmentAtivo = 0;

		}

		mGestureDetector = new GestureDetector(this, new GestureDetector.SimpleOnGestureListener() {
			@Override
			public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {
				Log.i(TAG, "x:" + velocityX);
				Log.i(TAG, "y:" + velocityY);
				if (velocityX < -1200.0f) {
					proximaArena();
				} else {
					if (velocityX > 850.0f) {
						previousArena();
					}
				}
				if (velocityY > 300 && (velocityX < 1000 && velocityX > -1000)) {
					// para cima avatar
					proximoAvatarCima();
				} else {
					if (velocityY < 1200 && (velocityX < 1000 && velocityX > -1000)) {
						// avatar para baixo
						proximoAvatarBaixo();
					}
				}

				return true;
			}
		});

		getSupportFragmentManager().addOnBackStackChangedListener(getListener());

	}

	public void abreCalendario(View v) {

		// calendario
		// Setup caldroid fragment
		// **** If you want normal CaldroidFragment, use below line ****
		caldroidFragment = new CaldroidFragment();

		// //////////////////////////////////////////////////////////////////////
		// **** This is to show customized fragment. If you want customized
		// version, uncomment below line ****
		// caldroidFragment = new CaldroidSampleCustomFragment();

		// Setup arguments

		Bundle args = new Bundle();
		Calendar cal = Calendar.getInstance();
		args.putInt(CaldroidFragment.MONTH, cal.get(Calendar.MONTH) + 1);
		args.putInt(CaldroidFragment.YEAR, cal.get(Calendar.YEAR));
		args.putBoolean(CaldroidFragment.ENABLE_SWIPE, true);
		args.putBoolean(CaldroidFragment.SIX_WEEKS_IN_CALENDAR, true);

		// Uncomment this to customize startDayOfWeek
		// args.putInt(CaldroidFragment.START_DAY_OF_WEEK,
		// CaldroidFragment.TUESDAY); // Tuesday
		caldroidFragment.setArguments(args);

		setCustomResourceForDates();

		new BuscarCalendario(user).execute();
		atualizaTitulo(R.string.calendario, R.drawable.topo_jogador);
		FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
		fragmentTransaction.replace(R.id.container, caldroidFragment);
		fragmentTransaction.addToBackStack("menu");
		fragmentTransaction.commit();
		ImageView img = (ImageView) findViewById(R.id.imgInfo);
		Button imgCalendario = (Button) findViewById(R.id.btnCalendario);

		img.setVisibility(View.INVISIBLE);
		imgCalendario.setVisibility(View.INVISIBLE);

	}

	protected void previousArena() {
		if (fragmentAtivo == 0) {
			Fragment frag = getSupportFragmentManager().findFragmentById(R.id.container);
			mFlipper = (ViewFlipper) frag.getView().findViewById(R.id.viewFlipper);
			mFlipper.setInAnimation(null);
			mFlipper.setOutAnimation(null);
			mFlipper.setInAnimation(this, android.R.anim.slide_in_left);
			mFlipper.setOutAnimation(this, android.R.anim.slide_out_right);

			TextView txtNomeArena = (TextView) frag.getView().findViewById(R.id.txtNomeArena);
			arenaAtiva = prefUtil.getArenaAtiva(getApplicationContext());
			int numArenas = new PreferenciasUtil().getNumeroArenas(getApplicationContext());
			arenas = getArenas(numArenas);
			if (arenas.size() < arenaAtiva + 1) {
				prefUtil.salvaArenaAtiva(0, getApplicationContext());
				arenaAtiva = 0;
				prefUtil.salvaArenaAtiva(arenaAtiva, getApplicationContext());
			}

			if (arenaAtiva > 0) {
				mFlipper.showPrevious();
				arenaAtiva--;
				txtNomeArena.setText(getString(arenas.get(arenaAtiva).getNome()));
				atulizaCirculo(frag, arenaAtiva);

				// salvaArena ativa
				prefUtil.salvaArenaAtiva(arenaAtiva, getApplicationContext());

			}

		}

		Log.i(TAG, "previousArena");
	}

	protected void proximaArena() {
		if (fragmentAtivo == 0) {
			Fragment frag = getSupportFragmentManager().findFragmentById(R.id.container);
			mFlipper = (ViewFlipper) frag.getView().findViewById(R.id.viewFlipper);

			mFlipper.setInAnimation(null);
			mFlipper.setOutAnimation(null);
			mFlipper.setInAnimation(inFromRightAnimation());
			mFlipper.setOutAnimation(outToLeftAnimation());

			TextView txtNomeArena = (TextView) frag.getView().findViewById(R.id.txtNomeArena);

			arenaAtiva = prefUtil.getArenaAtiva(getApplicationContext());
			int numArenas = new PreferenciasUtil().getNumeroArenas(getApplicationContext());
			arenas = getArenas(numArenas);

			if (arenaAtiva < numArenas - 1) {
				mFlipper.showNext();
				arenaAtiva++;

				txtNomeArena.setText(getString(arenas.get(arenaAtiva).getNome()));
				atulizaCirculo(frag, arenaAtiva);
				// salvaArena ativa
				prefUtil.salvaArenaAtiva(arenaAtiva, getApplicationContext());
			}
		}

		Log.i(TAG, "proximaArena");
	}

	protected void proximoAvatarBaixo() {
		if (fragmentAtivo == 0) {
			Fragment frag = getSupportFragmentManager().findFragmentById(R.id.container);
			mFlipper = (ViewFlipper) frag.getView().findViewById(R.id.flipAvatar);
			if (mFlipper.getDisplayedChild() < 4) {
				mFlipper.setInAnimation(null);
				mFlipper.setOutAnimation(null);
				mFlipper.setInAnimation(this, R.anim.abc_slide_in_bottom);
				//mFlipper.setOutAnimation(this, R.anim.abc_slide_out_top);
				mFlipper.showNext();

				atualizaTextoBarra(frag, mFlipper);

			}
		}
	}

	protected void proximoAvatarCima() {

		if (fragmentAtivo == 0) {
			Fragment frag = getSupportFragmentManager().findFragmentById(R.id.container);
			mFlipper = (ViewFlipper) frag.getView().findViewById(R.id.flipAvatar);
			if (mFlipper.getDisplayedChild() > 0) {
				mFlipper.setInAnimation(null);
				mFlipper.setOutAnimation(null);
				mFlipper.setInAnimation(this, R.anim.abc_slide_in_top);
				//mFlipper.setOutAnimation(this, R.anim.abc_slide_out_bottom);
				mFlipper.showPrevious();
				atualizaTextoBarra(frag, mFlipper);
			}
		}
	}

	private void atualizaTextoBarra(Fragment frag, ViewFlipper mFlipper2) {
		TextView txtEstado = (TextView) frag.getView().findViewById(R.id.txtSentimento);
		int viewAtivo = mFlipper2.getDisplayedChild();
		ImageView btn;
		Button btnOk = (Button) frag.getView().findViewById(R.id.btnInformarSintoma);
		//Button btnFarmacia = (Button) frag.getView().findViewById(R.id.btnFarmacia);
		Button btnHospital = (Button) frag.getView().findViewById(R.id.btnHospital);

		switch (viewAtivo) {
		case 0:
			txtEstado.setText(user.getNome() + ", " + getString(R.string.pergunta_sentimento_muito_bem));
			btnOk.setVisibility(View.VISIBLE);
			// mudar btn
			btn = (ImageView) frag.getView().findViewById(R.id.btnAmarelo);
			btn.setImageResource(R.drawable.btn_amarelo_ativo);

			btn = (ImageView) frag.getView().findViewById(R.id.btnLaranja);
			btn.setImageResource(R.drawable.btn_laranja_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVerde);
			btn.setImageResource(R.drawable.btn_verde_inativo);
			//btnFarmacia.setVisibility(View.INVISIBLE);
			btnHospital.setVisibility(View.INVISIBLE);

			break;
		case 1:
			txtEstado.setText(user.getNome() + ", " + getString(R.string.pergunta_sentimento_bem));
			btnOk.setVisibility(View.VISIBLE);
			btn = (ImageView) frag.getView().findViewById(R.id.btnLaranja);
			btn.setImageResource(R.drawable.btn_laranja_ativo);

			btn = (ImageView) frag.getView().findViewById(R.id.btnAmarelo);
			btn.setImageResource(R.drawable.btn_amarelo_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVerde);
			btn.setImageResource(R.drawable.btn_verde_inativo);
		//	btnFarmacia.setVisibility(View.INVISIBLE);
			btnHospital.setVisibility(View.INVISIBLE);
			break;
		case 2:
			txtEstado.setText(getString(R.string.pergunta_sentimento1));
			btnOk.setVisibility(View.INVISIBLE);

			btn = (ImageView) frag.getView().findViewById(R.id.btnVerde);
			btn.setImageResource(R.drawable.btn_verde_ativo);

			btn = (ImageView) frag.getView().findViewById(R.id.btnLaranja);
			btn.setImageResource(R.drawable.btn_laranja_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVermelho);
			btn.setImageResource(R.drawable.btn_vermelho_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnAmarelo);
			btn.setImageResource(R.drawable.btn_amarelo_inativo);
		//	btnFarmacia.setVisibility(View.INVISIBLE);
			btnHospital.setVisibility(View.INVISIBLE);
			break;
		case 3:
			txtEstado.setText(user.getNome() + ", " + getString(R.string.pergunta_sentimento4_mal));
			btnOk.setVisibility(View.VISIBLE);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVermelho);
			btn.setImageResource(R.drawable.btn_vermelho_ativo);

			btn = (ImageView) frag.getView().findViewById(R.id.btnVerde);
			btn.setImageResource(R.drawable.btn_verde_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVermelhoEscuro);
			btn.setImageResource(R.drawable.btn_vermelho_escuro_inativo);

			break;
		case 4:
			txtEstado.setText(user.getNome() + ", " + getString(R.string.pergunta_sentimento5_muito_mal));
			btnOk.setVisibility(View.VISIBLE);

			btn = (ImageView) frag.getView().findViewById(R.id.btnVermelhoEscuro);
			btn.setImageResource(R.drawable.btn_vermelho_escuro_ativo);

			btn = (ImageView) frag.getView().findViewById(R.id.btnVermelho);
			btn.setImageResource(R.drawable.btn_vermelho_inativo);
			btn = (ImageView) frag.getView().findViewById(R.id.btnVerde);
			btn.setImageResource(R.drawable.btn_verde_inativo);
			break;

		default:
			break;
		}

	}

	@SuppressLint("DefaultLocale")
	private void atualizaTitulo(int titulo, int cor) {
		// ImageView img = (ImageView) findViewById(R.id.imgTopoEsquerda);
		// if (img != null) {
		// if (titulo == R.string.saude) {
		// Animation anim = AnimationUtils.loadAnimation(this,
		// R.anim.abc_slide_in_top);
		// img.startAnimation(anim);
		// img.setVisibility(View.VISIBLE);
		// } else {
		// if (img.getVisibility() == View.VISIBLE) {
		// Animation anim = AnimationUtils.loadAnimation(this,
		// R.anim.abc_slide_out_top);
		// img.startAnimation(anim);
		// img.setVisibility(View.INVISIBLE);
		// }
		// }
		// }
		ImageView img = (ImageView) findViewById(R.id.imgInfo);
		Button imgCalendario = (Button) findViewById(R.id.btnCalendario);
		if (fragmentAtivo == 0) {
			img.setVisibility(View.VISIBLE);
			imgCalendario.setVisibility(View.VISIBLE);
		} else {

			img.setVisibility(View.INVISIBLE);
			imgCalendario.setVisibility(View.INVISIBLE);
		}
		RelativeLayout rl = (RelativeLayout) findViewById(R.id.llTopo);
		rl.setVisibility(View.VISIBLE);
		rl.setBackgroundResource(cor);
		TextView text = (TextView) findViewById(R.id.barra_titulo);
		text.setText(getString(titulo).toUpperCase());
		if (titulo == R.string.prevencao_acidentes)
			text.setTextSize(getResources().getDimension(R.dimen.fonte_menor));
		else
			text.setTextSize(getResources().getDimension(R.dimen.fonte_maior));
		text.setBackgroundResource(cor);
	}

	private OnBackStackChangedListener getListener() {
		OnBackStackChangedListener result = new OnBackStackChangedListener() {
			public void onBackStackChanged() {
				FragmentManager manager = getSupportFragmentManager();
				// manager.findFragmentById(0).get
				if (manager != null) {
					int backStackEntryCount = manager.getBackStackEntryCount();
					if (backStackEntryCount == 0) {
						finish();
					}
					try {
						Fragment fragment = manager.getFragments().get(backStackEntryCount - 1);
						// tela sentimento
						if (fragment.getClass().getName().equals("br.com.epitrack.healthycup.SentimentoActivity$SentimentoFragment")) {
							fragmentAtivo = 0;
							atualizaTitulo(R.string.jogador, R.drawable.topo_jogador);
							ImageView btn = (ImageView) findViewById(R.id.menu_item_jogador);
							resetaBotoes(btn, R.drawable.icone_jogador_on);
							Log.i(TAG, "fragmentAtivo");
						}

						// se for a tela de opção de saude mudar o titulo
						if (fragment.getClass().getName().equals("br.com.epitrack.healthycup.SentimentoActivity$SaudeFragment")) {
							fragmentAtivo = 1;
							atualizaTitulo(R.string.saude, R.drawable.topo_saude);
							ImageView btn = (ImageView) findViewById(R.id.menu_item_saude);
							resetaBotoes(btn, R.drawable.icone_saude_on);
						}

						// se for a tela de mais opcoes
						if (fragment.getClass().getName().equals("br.com.epitrack.healthycup.SentimentoActivity$MenuFragment")) {
							fragmentAtivo = 4;
							RelativeLayout rl = (RelativeLayout) findViewById(R.id.llTopo);
							rl.setVisibility(View.GONE);
						}
						// se for a tela de jogos
						if (fragment.getClass().getName().equals("br.com.epitrack.healthycup.SentimentoActivity$JogosFragment")) {
							fragmentAtivo = 2;
							atualizaTitulo(R.string.jogos,R.drawable.topo_jogos);
							ImageView btn = (ImageView) findViewById(R.id.menu_item_jogos);
							resetaBotoes(btn, R.drawable.icone_jogos_on);
						}
					} catch (Exception e) {
						finish();
					}

				}
			}
		};

		return result;
	}

	@Override
	protected void onPause() {
		super.onPause();
		// salvar a arena ativa
		Log.i(TAG, "onPause");
		new SalvarArena(arenaAtiva, user).execute();
	}

	@Override
	protected void onResume() {
		super.onResume();
		// verificar se houve mudança na localizacao
		// new UserDAO().atualizaLocalizacao(this);

		// - Check NETWORK_PROVIDER for an existing location reading.
		// Only keep this last reading if it is fresh - less than 5 minutes old.
		if (null == (mLocationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE)))
			finish();// Leave if true!

		boolean network_enabled = mLocationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
		boolean gps_enabled = mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
		if (!network_enabled && !gps_enabled) {
			new AlertDialog.Builder(this).setTitle(getString(R.string.atencao)).setMessage(getString(R.string.sem_localizacao))
					.setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
						public void onClick(DialogInterface dialog, int which) {
							startActivityForResult(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS), 0);
						}
					}).show();

		} else {
			String tpLocation = null;
			Location location = null;
			if (network_enabled) {
				location = mLocationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
				tpLocation = LocationManager.NETWORK_PROVIDER;
			} else {
				if (gps_enabled) {
					location = mLocationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
					tpLocation = LocationManager.GPS_PROVIDER;
				}
			}
			if (location != null) {
				if (age(location) < FIVE_MINS) {
					mLastLocationReading = location;
					this.onLocationChanged(mLastLocationReading);
				}
			}
			// - register to receive location updates from NETWORK_PROVIDER
			mLocationManager.requestLocationUpdates(tpLocation, mMinTime, mMinDistance, this);
		}

	}

	@Override
	public void onLocationChanged(Location currentLocation) {

		if (mLastLocationReading == null || age(currentLocation) < age(mLastLocationReading)) {
			mLastLocationReading = currentLocation;

		}

	}

	public void showInfo(View view) {
		startActivity(new Intent(this, InformacoesActivity.class));
	}

	public static List<Arena> getArenas(int numArenas) {
		List<Arena> arenas = new ArenaDAO().getArenas();

		List<Arena> arenasRetorno = new ArrayList<Arena>();
		for (int i = 0; i < numArenas; i++) {
			arenasRetorno.add(arenas.get(i));
		}

		return arenasRetorno;
	}

	private Animation inFromRightAnimation() {
		Animation inFromRight = new TranslateAnimation(Animation.RELATIVE_TO_PARENT, +1.0f, Animation.RELATIVE_TO_PARENT, 0.0f,
				Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, 0.0f);
		inFromRight.setDuration(500);
		inFromRight.setInterpolator(new LinearInterpolator());
		return inFromRight;
	}

	private Animation outToLeftAnimation() {
		Animation outtoLeft = new TranslateAnimation(Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, -1.0f,
				Animation.RELATIVE_TO_PARENT, 0.0f, Animation.RELATIVE_TO_PARENT, 0.0f);
		outtoLeft.setDuration(500);
		outtoLeft.setInterpolator(new LinearInterpolator());
		return outtoLeft;
	}

	private void atulizaCirculo(Fragment frag, int ativa) {
		View circulo;
		;
		for (int i = 0; i < arenas.size(); i++) {
			circulo = (View) frag.getView().findViewById(i);
			if (i == ativa) {
				circulo.setBackgroundResource(R.drawable.circulo_arena);
			} else {
				circulo.setBackgroundResource(R.drawable.circulo_unselect);
			}

		}

	}

	@Override
	public boolean onTouchEvent(MotionEvent event) {
		return mGestureDetector.onTouchEvent(event);
	}

	private long age(Location location) {
		return System.currentTimeMillis() - location.getTime();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {

		// Inflate the menu; this adds items to the action bar if it is present.
		// getMenuInflater().inflate(R.menu.sentimento, menu);
		return true;
	}

	public boolean abreFragment(View v1) {
		Fragment fragment = null;
		ImageView v;
		FragmentTransaction fragmentTransaction;
		int id = v1.getId();

		switch (id) {

		case R.id.menu_item_jogador:

			atualizaTitulo(R.string.jogador, R.drawable.topo_jogador);

			fragmentAtivo = 0;
			v = (ImageView) v1;
			resetaBotoes(v, R.drawable.icone_jogador_on);
			// busca o fragment adequado
			SentimentoFragment sentimentoFrag = new SentimentoFragment();
			Bundle args = new Bundle();
			PreferenciasUtil util = new PreferenciasUtil();
			args.putString("usuario", util.getUserCompleto(this));
			sentimentoFrag.setArguments(args);
			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.replace(R.id.container, sentimentoFrag);
			fragmentTransaction.addToBackStack(null);
			fragmentTransaction.commit();
			ImageView img = (ImageView) findViewById(R.id.imgInfo);
			img.setVisibility(View.VISIBLE);
			new BuscarCalendario(user).execute();
			return true;

			// TODO fragment saude
		case R.id.menu_item_saude:
			fragmentAtivo = 1;
			atualizaTitulo(R.string.saude, R.drawable.topo_saude);

			v = (ImageView) v1;
			resetaBotoes(v, R.drawable.icone_saude_on);

			fragment = new SaudeFragment();
			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.replace(R.id.container, fragment);
			fragmentTransaction.addToBackStack("saude");
			fragmentTransaction.commit();
			return true;

			// saiba mais
		case R.id.menu_item_turismo:
			fragmentAtivo = 3;
			atualizaTitulo(R.string.saibamais, R.drawable.topo_saibamais);

			v = (ImageView) v1;
			resetaBotoes(v, R.drawable.ico_mais_on);

			fragment = new SaibaMaisFragment();
			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.replace(R.id.container, fragment);
			fragmentTransaction.addToBackStack("saude");
			fragmentTransaction.commit();
			return true;

		case R.id.menu_item_jogos:
			fragmentAtivo = 2;
			atualizaTitulo(R.string.jogos,R.drawable.topo_jogos);

			v = (ImageView) v1;
			resetaBotoes(v, R.drawable.icone_jogos_on);

			fragment = new JogosFragment();
			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.replace(R.id.container, fragment);
			fragmentTransaction.addToBackStack("jogos");
			fragmentTransaction.commit();
			return true;

		case R.id.menu_outrasopcoes:
			RelativeLayout rl = (RelativeLayout) findViewById(R.id.llTopo);
			rl.setVisibility(View.GONE);
			// ImageView img = (ImageView) findViewById(R.id.imgTopoEsquerda);
			// if (img.getVisibility() == View.VISIBLE) {
			// Animation anim = AnimationUtils.loadAnimation(this,
			// R.anim.abc_slide_out_top);
			// img.startAnimation(anim);
			// img.setVisibility(View.INVISIBLE);
			// }

			fragmentAtivo = 4;
			v = (ImageView) v1;
			resetaBotoes(v, 0);

			fragment = new MenuFragment();
			fragmentTransaction = getSupportFragmentManager().beginTransaction();
			fragmentTransaction.replace(R.id.container, fragment);
			fragmentTransaction.addToBackStack("menu");
			fragmentTransaction.commit();
			return true;

		default:
			break;
		}
		return false;
	}

	private void resetaBotoes(ImageView v, int iconeAtivo) {
		ImageView img = (ImageView) findViewById(R.id.menu_item_jogador);
		img.setImageResource(R.drawable.icone_jogador_off);

		img = (ImageView) findViewById(R.id.menu_item_jogos);
		img.setImageResource(R.drawable.icone_jogos_cinza);

		img = (ImageView) findViewById(R.id.menu_item_saude);
		img.setImageResource(R.drawable.icone_saude_off);

		img = (ImageView) findViewById(R.id.menu_item_turismo);
		img.setImageResource(R.drawable.ico_mais_off);

		// LinearLayout ll = (LinearLayout) findViewById(R.id.ll_outrasopcoes);
		// ll.setVisibility(View.INVISIBLE);
		if (iconeAtivo > 0)
			v.setImageResource(iconeAtivo);

	}

	public void abreProtejaGol(View v) {
		Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://unaids.org.br/protejaogol/proteja-o-gol/"));
		startActivity(browserIntent);
	}

	@SuppressLint("DefaultLocale")
	public void abreTextos(View v) {

		String pagina = "_" + Locale.getDefault().getDisplayLanguage().substring(0, 3).toLowerCase() + ".html";
		int titulo = 0;
		int cor = R.drawable.topo_saude;
		switch (v.getId()) {
		case R.id.btnPrevencaoDoencas:
			pagina = "prev_doencas" + pagina;
			titulo = R.string.prevencao_doecas;
			break;

		case R.id.btnPrevencaoAcidentes:
			pagina = "prev_acidentes" + pagina;
			titulo = R.string.prevencao_acidentes;
			break;

		case R.id.btnPrevencaoViolencia:
			pagina = "prev_violencia" + pagina;
			titulo = R.string.prevencao_violencia;
			break;

		case R.id.btnCuidadosBasico:
			pagina = "cuidado" + pagina;
			titulo = R.string.higiene;
			break;
		case R.id.btnVacinas:
			pagina = "vacinas" + pagina;
			titulo = R.string.vacinas;
			break;
		case R.id.btnUrgencia:
			pagina = "urgencia" + pagina;
			titulo = R.string.urgencia_emergencias;
			break;
		case R.id.btnMalaLegal:
			pagina = "mala" + pagina;
			titulo = R.string.malalegal;
			cor = R.drawable.topo_saibamais;
			break;

		default:
			break;
		}
		atualizaTitulo(titulo, cor);
		Bundle args = new Bundle();
		WebviewFragment detailFragment = new WebviewFragment();
		args.putString("pagina", pagina);
		detailFragment.setArguments(args);
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreSaibaMais(View v) {
		atualizaTitulo(R.string.saibamais, R.drawable.topo_saude);
	}

	public void abreDashBoard(View v) {
		Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://saudenacopa.epitrack.com.br"));
		startActivity(browserIntent);
	}

	/**
	 * Metodos do click em saude
	 */
	public void abreTelefones(View v) {
		atualizaTitulo(R.string.telefones_uteis, R.drawable.topo_saude);

		TelefonesFragment detailFragment = new TelefonesFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreHospitais(View v) {

		// verificar se tem internet
		if (!new PreferenciasUtil(getApplicationContext()).isOnline()) {
			// mostrar mensagem que somente conectado pode enviar o sentimento
			Toast.makeText(getApplicationContext(), getString(R.string.erro_sem_internet), Toast.LENGTH_LONG).show();
			return;
		}
		fragmentAtivo = 1;
		ImageView btn = (ImageView) findViewById(R.id.menu_item_saude);
		resetaBotoes(btn, R.drawable.icone_saude_on);
		atualizaTitulo(R.string.hospitais_ref, R.drawable.topo_saude);
		MapaFragment detailFragment = new MapaFragment();

		// passa o argumento
		Bundle args = new Bundle();
		args.putString("query", "Hospital");
		detailFragment.setArguments(args);

		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreFarmacias(View v) {
		// verificar se tem internet
		if (!new PreferenciasUtil(getApplicationContext()).isOnline()) {
			// mostrar mensagem que somente conectado pode enviar o sentimento
			Toast.makeText(getApplicationContext(), getString(R.string.erro_sem_internet), Toast.LENGTH_LONG).show();
			return;
		}
		Toast.makeText(getApplicationContext(), getString(R.string.info_farmacias), Toast.LENGTH_LONG).show();
		fragmentAtivo = 1;
		ImageView btn = (ImageView) findViewById(R.id.menu_item_saude);
		resetaBotoes(btn, R.drawable.icone_saude_on);
		atualizaTitulo(R.string.farmacias, R.drawable.topo_saude);

		MapaFragment detailFragment = new MapaFragment();
		// passa o argumento
		Bundle args = new Bundle();
		args.putString("query", "Farmacia");
		detailFragment.setArguments(args);
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void voltarSaude(View v) {

		TextView text = (TextView) findViewById(R.id.barra_titulo);
		text.setText(getString(R.string.saude));
		ScrollView rlLembrete = (ScrollView) findViewById(R.id.rlLembretes);
		rlLembrete.setVisibility(View.INVISIBLE);
		ListView lvTel = (ListView) findViewById(R.id.listView1);
		lvTel.setVisibility(View.INVISIBLE);
		RelativeLayout rl = (RelativeLayout) findViewById(R.id.botoesIniciais);
		rl.setVisibility(View.VISIBLE);
		// ImageView imgVoltar = (ImageView) findViewById(R.id.imgVoltar);
		// imgVoltar.setVisibility(View.INVISIBLE);

	}

	public void sair(View v) {
		// apagar infos do sharedPreferences
		SharedPreferences sharedPref = getSharedPreferences(getString(R.string.id_key_preferencias), Context.MODE_PRIVATE);

		SharedPreferences.Editor editor = sharedPref.edit();
		editor.putString("email", null);
		editor.putString("senha", null);
		editor.putString("user", null);
		editor.putString("calendario", null);
		editor.commit();

		// ir para tela de login
		Intent intent = new Intent(this, LoginActivity.class);
		intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
		startActivity(intent);
	}

	public void abreDenunciarProblema(View v) {
		if (!new PreferenciasUtil(getApplicationContext()).isOnline()) {
			// mostrar mensagem que somente conectado pode enviar o sentimento
			Toast.makeText(getApplicationContext(), getString(R.string.erro_sem_internet), Toast.LENGTH_LONG).show();
			return;
		}
		DenunciarFragment detailFragment = new DenunciarFragment();
		// passa o argumento
		Bundle args = new Bundle();
		Gson gson = new Gson();
		args.putString("user", gson.toJson(user));
		detailFragment.setArguments(args);
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreTermosPoliticas(View v) {
		SobreTermosFragment detailFragment = new SobreTermosFragment();
		// passa o argumento
		Bundle args = new Bundle();

		args.putString("pagina", "termos");
		detailFragment.setArguments(args);
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreVersao(View v) {
		VersaoFragment detailFragment = new VersaoFragment();

		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreEpitrack(View v) {
		Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://www.epitrack.com.br"));
		startActivity(browserIntent);
	}

	public void abreSobre(View v) {
		SobreTermosFragment detailFragment = new SobreTermosFragment();
		// passa o argumento
		Bundle args = new Bundle();
		args.putString("pagina", "sobre");
		detailFragment.setArguments(args);
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreAlterarSenha(View v) {
		if (!new PreferenciasUtil(getApplicationContext()).isOnline()) {
			// mostrar mensagem que somente conectado pode enviar o sentimento
			Toast.makeText(getApplicationContext(), getString(R.string.erro_sem_internet), Toast.LENGTH_LONG).show();
			return;
		}
		AlterarSenhaFragment detailFragment = new AlterarSenhaFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	// fragments do saiba mais
	public void abreItensJogo(View v) {
		ImageView imgBotao = (ImageView) findViewById(R.id.menu_item_turismo);
		resetaBotoes(imgBotao, R.drawable.ico_mais_on);
		fragmentAtivo = 2;
		atualizaTitulo(R.string.itens_jogo, R.drawable.topo_saibamais);
		ItensJogo detailFragment = new ItensJogo();
		// passa o argumento

		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	public void abreCategorias(View v) {
		fragmentAtivo = 2;
		atualizaTitulo(R.string.categorias, R.drawable.topo_saibamais);
		ImageView imgBotao = (ImageView) findViewById(R.id.menu_item_turismo);
		resetaBotoes(imgBotao, R.drawable.ico_mais_on);
		CategoriaFragment detailFragment = new CategoriaFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreLinkUteis(View v) {
		atualizaTitulo(R.string.linksuteis, R.drawable.topo_saibamais);

		LinksFragment detailFragment = new LinksFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreConsulados(View v) {
		atualizaTitulo(R.string.consulados, R.drawable.topo_saibamais);

		EmbaixadasFragment detailFragment = new EmbaixadasFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreArenas(View v) {
		fragmentAtivo = 2;
		atualizaTitulo(R.string.arenas, R.drawable.topo_jogos);
		ImageView imgBotao = (ImageView) findViewById(R.id.menu_item_jogos);
		resetaBotoes(imgBotao, R.drawable.icone_jogos_on);
		ArenasFragment detailFragment = new ArenasFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();

	}

	public void abreCalendarioJogos(View v) {
		atualizaTitulo(R.string.calendario_jogos, R.drawable.topo_jogos);

		CalendarioJogosFragment detailFragment = new CalendarioJogosFragment();
		getSupportFragmentManager().beginTransaction().replace(R.id.container, detailFragment).addToBackStack("back").commit();
	}

	/**
	 * Fragment para dizer como está se sentindo
	 */
	public static class SentimentoFragment extends Fragment {

		ViewFlipper mViewFlipper;
		TextView txtEstado;
		Usuario user;
		View rootView;
		ListView lvSintomas;
		String[] sintomas;
		Sentimento sentimento;
		ImageAdapter adapter;
		Button btnOk;

		public SentimentoFragment() {
		}

		@Override
		public void onActivityCreated(Bundle savedInstanceState) {
			Log.i(TAG, "SentimentoFragment onActivityCreated");
			super.onActivityCreated(savedInstanceState);
			mViewFlipper = (ViewFlipper) getView().findViewById(R.id.flipAvatar);

		}

		@Override
		public void onAttach(Activity activity) {
			Log.i(TAG, "SentimentoFragment onAttach");
			super.onAttach(activity);

		}

		@Override
		public void onResume() {
			Log.i(TAG, "SentimentoFragment onResume ");
			super.onResume();
		}

		// @Override
		// public void onActivityCreated (Bundle savedInstanceState){
		// Log.i(TAG,"onActivityCreated");
		// // lv = (ListView) getActivity().findViewById(R.id.listView1);
		// // lv.smoothScrollBy(200, 2000);
		// }

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
			Log.i(TAG, "onCreateView");

			// busca as infos passadas
			Bundle args = getArguments();
			Gson gson = new Gson();
			user = gson.fromJson(args.getString("usuario"), Usuario.class);

			rootView = inflater.inflate(R.layout.fragment_main, container, false);

			// rlSentimento.setBackgroundResource(R.drawable.arena_brasilia);

			// montar telas
			int arenaAtiva = new PreferenciasUtil().getArenaAtiva(rootView.getContext());
			sentimento = new Sentimento();

			// busca o numero de estadios disponivels
			sentimento.setNumEstadioDisponivel(sentimento.calcularQuantidadeArena(user.getEngajamento()));

			List<Arena> arenas = getArenas(sentimento.getNumEstadioDisponivel());
			new PreferenciasUtil().salvaNumeroArenas(arenas.size(), rootView.getContext());

			// CRIAR RELATIVE LAYOUTS
			sentimento.setBackgroundsArenas(rootView, arenas, arenaAtiva);

			// selecionar arena ativa
			TextView txtNomeArena = (TextView) rootView.findViewById(R.id.txtNomeArena);
			if (arenas.size() < arenaAtiva + 1) {
				arenaAtiva = 0;
			}
			txtNomeArena.setText(getString(arenas.get(arenaAtiva).getNome()));
			// ViewFlipper mFlipper = (ViewFlipper)
			// rootView.findViewById(R.id.viewFlipper);
			// mFlipper.setDisplayedChild(arenaAtiva);

			// montar botoes de numero de estadios
			sentimento.montaBotoesEstadio(rootView, arenas, arenaAtiva);

			// popula o user com as imagens corretas
			user.atualizaImagens();

			sentimento.setId(3);
			// monta a tela
			sentimento.montaTela(user, rootView);

			// busca o botao
			btnOk = (Button) rootView.findViewById(R.id.btnInformarSintoma);
			btnOk.setVisibility(View.INVISIBLE);
			btnOk.setOnClickListener(new OnClickListener() {

				// enviar sentimento
				@Override
				public void onClick(View v) {

					// verificar se tem internet
					if (!new PreferenciasUtil(rootView.getContext()).isOnline()) {
						// mostrar mensagem que somente conectado pode enviar o
						// sentimento
						Toast.makeText(rootView.getContext(), getString(R.string.erro_sem_internet), Toast.LENGTH_LONG).show();
						return;
					}

					// zerar sintomas
					zerarSintomas();

					// verificar se o sintoma é mal ou muitoMal
					int position = mViewFlipper.getDisplayedChild();
					sentimento = new Sentimento();
					sentimento.setId(position);
					if (position > 2) {
						// está mal ou muito mal
						abreJanelaSintomas();

					} else {
						if (position == 2) {
							new AlertDialog.Builder(rootView.getContext()).setTitle(getString(R.string.atencao))
									.setMessage(getString(R.string.selecione_opcao))
									.setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
										public void onClick(DialogInterface dialog, int which) {

										}
									}).show();

						} else {
							abreConfirmacao();
						}

					}

				}

				private void abreJanelaSintomas() {

					// zerar os sintomas
					AlertDialog.Builder ad = new AlertDialog.Builder(rootView.getContext());
					// ad.setIcon(R.drawable.icon);
					TextView myMsg = new TextView(rootView.getContext());
					myMsg.setText(getString(R.string.sintomas));
					myMsg.setGravity(Gravity.CENTER_HORIZONTAL);
					myMsg.setTextSize(20);
					myMsg.setTextColor(Color.WHITE);
					myMsg.setBackgroundColor(rootView.getResources().getColor(R.color.bandeira_amarelo));

					// ad.setCustomTitle(myMsg);
					View viewSintomas = LayoutInflater.from(rootView.getContext()).inflate(R.layout.dialog_sintomas, null);
					lvSintomas = (ListView) viewSintomas.findViewById(R.id.lvSintomas);
					sintomas = getResources().getStringArray(R.array.sintomas);
					SintomasAdapter adapter = new SintomasAdapter(getActivity(), sintomas);

					lvSintomas.setAdapter(adapter);

					ad.setView(viewSintomas);

					ad.setPositiveButton("OK", new android.content.DialogInterface.OnClickListener() {
						public void onClick(DialogInterface dialog, int arg1) {

							// abreConfirmacao();
							// envia sintomas
							prepararEnviarSintomas();
						}

					});

					ad.setOnCancelListener(new DialogInterface.OnCancelListener() {
						public void onCancel(DialogInterface dialog) {
							// OK, go back to Main menu
						}
					});

					ad.show();

				}
			});

			ArrayList<Integer> avatares = user.getImagensSentimentos();
			// montar avatar
			mViewFlipper = (ViewFlipper) rootView.findViewById(R.id.flipAvatar);
			View v1 = inflater.inflate(R.layout.item_adapter_sentimento, null);
			ImageView imgAvatar = (ImageView) v1.findViewById(R.id.imgTrofeu);
			imgAvatar.setImageResource(avatares.get(0));
			View v2 = inflater.inflate(R.layout.item_adapter_sentimento, null);
			ImageView imgAvatar2 = (ImageView) v2.findViewById(R.id.imgTrofeu);
			imgAvatar2.setImageResource(avatares.get(1));
			View v3 = inflater.inflate(R.layout.item_adapter_sentimento, null);
			ImageView imgAvatar3 = (ImageView) v3.findViewById(R.id.imgTrofeu);
			imgAvatar3.setImageResource(avatares.get(2));
			View v4 = inflater.inflate(R.layout.item_adapter_sentimento, null);
			ImageView imgAvatar4 = (ImageView) v4.findViewById(R.id.imgTrofeu);
			imgAvatar4.setImageResource(avatares.get(3));
			View v5 = inflater.inflate(R.layout.item_adapter_sentimento, null);
			ImageView imgAvatar5 = (ImageView) v5.findViewById(R.id.imgTrofeu);
			imgAvatar5.setImageResource(avatares.get(4));

			// Add the views to the flipper
			mViewFlipper.addView(v1);
			mViewFlipper.addView(v2);
			mViewFlipper.addView(v3);
			mViewFlipper.addView(v4);
			mViewFlipper.addView(v5);

			mViewFlipper.setDisplayedChild(2);

			// Timer timer = new Timer();
			//
			// // TODO se primeira vez animar
			// timer.schedule(new TimerTask() {
			// @Override
			// public void run() {
			//
			// // When you need to modify a UI element, do so on the UI
			// // thread.
			// // 'getActivity()' is required as this is being ran from a
			// // Fragment.
			// getActivity().runOnUiThread(new Runnable() {
			//
			// @Override
			// public void run() {
			// // This code will always run on the UI thread,
			// // therefore is safe to modify UI elements.
			//
			// ViewFlipper vf = (ViewFlipper)
			// getActivity().findViewById(R.id.flipAvatar);
			// if (vf != null) {
			// if (vf.getDisplayedChild() != 1) {
			//
			// vf.setInAnimation(null);
			// vf.setOutAnimation(null);
			// vf.setInAnimation(getActivity().getApplicationContext(),
			// R.anim.abc_slide_in_bottom);
			// vf.setOutAnimation(getActivity().getApplicationContext(),
			// R.anim.abc_slide_out_top);
			//
			// }
			// }
			//
			// }
			// });
			// }
			// }, 0, 1000); // End of your timer code.

			// atualizaTextoBarra(frag, mFlipper);

			// TODO acesso primeira vez, animar o listview
			// Animation anim =
			// AnimationUtils.loadAnimation(getActivity().getApplicationContext(),
			// R.anim.down_from_top);
			// lv.startAnimation(anim);

			return rootView;
		}

		protected void zerarSintomas() {

			SharedPreferences sharedPref = rootView.getContext().getSharedPreferences(rootView.getContext().getString(R.string.id_key_preferencias),
					Context.MODE_PRIVATE);

			SharedPreferences.Editor editor = sharedPref.edit();
			editor.putString(rootView.getContext().getString(R.string.id_key_sentimentos), null);
			editor.commit();

		}

		protected void abreConfirmacao() {

			// monta array de textos de confirmação de como está se sentindo
			String[] txtConfirmacao = getResources().getStringArray(R.array.txt_sentimentos);

			// pega o primeiro item visivel
			int position = mViewFlipper.getDisplayedChild();
			if (position > 2) {
				position--;
			}

			AlertDialog.Builder customBuilder = new AlertDialog.Builder(new ContextThemeWrapper(rootView.getContext(),
					android.R.style.Theme_Holo_Light_Dialog));
			customBuilder.setMessage(user.getNome() + "," + txtConfirmacao[position]);
			customBuilder.setPositiveButton(R.string.confirmar, new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int which) {

					prepararEnviarSintomas();
					// continue enviar sintomas
					// buscar sintomas selecionados

				}
			});

			customBuilder.setNegativeButton(android.R.string.no, new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int which) {

				}
			});
			AlertDialog dialog = customBuilder.create();
			dialog.show();

			Button b = dialog.getButton(DialogInterface.BUTTON_NEGATIVE);
			if (b != null) {
				b.setBackgroundColor(getResources().getColor(R.color.verde_botao));
				b.setTextColor(getResources().getColor(R.color.white));
			}

			b = dialog.getButton(DialogInterface.BUTTON_POSITIVE);
			if (b != null) {
				b.setBackgroundColor(getResources().getColor(R.color.verde_botao));
				b.setTextColor(getResources().getColor(R.color.white));
			}

		}

		protected void prepararEnviarSintomas() {
			List<Boolean> sintomasSelecionados = getSintomasSelecionados();

			if (sentimento.getId() > 2) {
				// tem que selecionar um sentimento pelo menos
				if (null == sintomasSelecionados) {
					alertaMostraSentimento();
					return;
				} else {
					if (!sintomasSelecionados.contains(true)) {
						alertaMostraSentimento();
						return;
					}
				}
			}

			sentimento.setCampos(new int[12]);
			for (int i = 0; i < 12; i++) {
				if (sintomasSelecionados.get(i)) {
					sentimento.getCampos()[i] = 1;
				} else {
					sentimento.getCampos()[i] = 0;
				}
			}

			// se nao tiver nenhum user no preferences, usa o
			// que veio do fragment
			String usuario = new PreferenciasUtil().getUserCompleto(getActivity());
			if (usuario == null) {
				sentimento.setUser(user);
			} else {
				Gson gson = new Gson();
				sentimento.setUser(gson.fromJson(usuario, Usuario.class));
			}

			// busca latitude e longitude
			Location l = user.getLocalizacao(rootView.getContext());
			if (l == null) {
				// não foi possivel pegar uma localidade
				new AlertDialog.Builder(rootView.getContext()).setTitle(getString(R.string.atencao)).setMessage(getString(R.string.sem_localizacao))
						.setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
							public void onClick(DialogInterface dialog, int which) {

							}
						}).show();
				return;

			} else {
				sentimento.setLatitude(String.valueOf(l.getLatitude()));
				sentimento.setLongitude(String.valueOf(l.getLongitude()));

				// esconde botao e mostra loading
				btnOk = (Button) rootView.findViewById(R.id.btnInformarSintoma);
				btnOk.setVisibility(View.INVISIBLE);
				ProgressBar pbar = (ProgressBar) rootView.findViewById(R.id.progressBar1);
				pbar.setVisibility(View.VISIBLE);

				// envia
				new EnviarSentimento(sentimento, rootView, getActivity(), mViewFlipper).execute();

				// mostra os botoes de hospitais e farmácias caso esteja mal ou
				// muito mal
				//Button btnFarmacia = (Button) rootView.findViewById(R.id.btnFarmacia);
				Button btnHospital = (Button) rootView.findViewById(R.id.btnHospital);
				if (sentimento.getId() > 2) {
					//btnFarmacia.setVisibility(View.VISIBLE);
					btnHospital.setVisibility(View.VISIBLE);
				}

			}

		}

		private void alertaMostraSentimento() {
			// tem que selecionar um sentimento pelo menos
			new AlertDialog.Builder(rootView.getContext()).setTitle(getString(R.string.atencao))
					.setMessage(getString(R.string.selecione_um_sentimento))
					.setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
						public void onClick(DialogInterface dialog, int which) {

						}
					}).show();
		}

		protected List<Boolean> getSintomasSelecionados() {
			Gson gson = new Gson();

			List<Boolean> sentimentosSelecionados = new ArrayList<Boolean>();
			// inserir no sharedPref o array de favoritos o
			// idCadastro
			// do politico

			SharedPreferences sharedPref = rootView.getContext().getSharedPreferences(rootView.getContext().getString(R.string.id_key_preferencias),
					Context.MODE_PRIVATE);
			sentimentosSelecionados = gson.fromJson(sharedPref.getString(getString(R.string.id_key_sentimentos), null),
					new TypeToken<List<Boolean>>() {
					}.getType());
			if (null == sentimentosSelecionados) {
				sentimentosSelecionados = new ArrayList<Boolean>();
				for (int i = 0; i < 12; i++) {
					sentimentosSelecionados.add(false);
				}
			}

			return sentimentosSelecionados;
		}

	}

	/**
	 * Fragment saiba mais
	 */
	public static class SaibaMaisFragment extends Fragment {

		public SaibaMaisFragment() {

		}

		private static View view;

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

			if (view != null) {
				ViewGroup parent = (ViewGroup) view.getParent();
				if (parent != null)
					parent.removeView(view);
			}
			try {
				view = inflater.inflate(R.layout.saiba_mais_fragment, container, false);
			} catch (InflateException e) {
				/* map is already there, just return view as it is */
			}
			// buscar tweets se primeira tela
			// if (text.getText().toString().equals(getString(R.string.saude)))
			// {

			PreferenciasUtil pu = new PreferenciasUtil(container.getContext());
			if (pu.isOnline())
				new BuscarTweets("minsaude", view, getActivity(), (ProgressBar) getActivity().findViewById(R.id.progressBar1)).execute();
			// }

			return view;
		}

		/**
		 * BuscarTweets
		 *
		 * @author Guto
		 *
		 */

		public class BuscarTweets extends AsyncTask<Void, Void, String> {

			private View view;
			private String name;
			private Activity context;
			private ProgressBar pb;

			public BuscarTweets(String name, View view, Activity context, ProgressBar progressBar) {
				this.view = view;
				this.name = name;
				this.context = context;
				pb = progressBar;
				pb.setVisibility(View.VISIBLE);
			}

			@Override
			protected String doInBackground(Void... arg0) {

				return new SaudeDAO().buscaTwitter(name);
			}

			protected void onPostExecute(String result) {
				try {
					ListView lvT = (ListView) view.findViewById(R.id.lvTwitter1);
					if (lvT != null) {
						List<Tweet> tweets = new ArrayList<Tweet>();

						JSONObject jObject = new JSONObject(result);
						JSONArray jArray = jObject.getJSONArray("results");
						for (int i = 0; i < jArray.length(); i++) {
							Tweet t = new Tweet();
							JSONObject obj = jArray.getJSONObject(i);
							t.setTexto(obj.getString("text"));
							t.setTempo(obj.getString("created_at"));

							JSONObject user = obj.getJSONObject("user");

							t.setNick(user.getString("name"));
							t.setNome("@" + user.getString("screen_name"));
							tweets.add(t);
						}

						// pegar o list view

						TweetAdapter adapter = new TweetAdapter(context, tweets);
						lvT.setAdapter(adapter);
					}

				} catch (JSONException e) {
					e.printStackTrace();
				}
				pb.setVisibility(View.INVISIBLE);
			}

		}
	}

	/**
	 * Fragment menu
	 */
	public static class MenuFragment extends Fragment {

		public MenuFragment() {

		}

		private static View view;

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

			if (view != null) {
				ViewGroup parent = (ViewGroup) view.getParent();
				if (parent != null)
					parent.removeView(view);
			}
			try {
				view = inflater.inflate(R.layout.menu_fragment, container, false);
			} catch (InflateException e) {
				/* map is already there, just return view as it is */
			}

			return view;
		}
	}

	/**
	 * Fragment informações de saúde
	 */
	public static class JogosFragment extends Fragment {

		public JogosFragment() {

		}

		private static View view;

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

			if (view != null) {
				ViewGroup parent = (ViewGroup) view.getParent();
				if (parent != null)
					parent.removeView(view);
			}
			try {
				view = inflater.inflate(R.layout.jogos_fragment, container, false);
			} catch (InflateException e) {
				/* map is already there, just return view as it is */
			}

			return view;
		}

	}

	/**
	 * Fragment informações de saúde
	 */
	public static class SaudeFragment extends Fragment {

		public SaudeFragment() {

		}

		private static View view;

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

			if (view != null) {
				ViewGroup parent = (ViewGroup) view.getParent();
				if (parent != null)
					parent.removeView(view);
			}
			try {
				view = inflater.inflate(R.layout.saude_fragment, container, false);
			} catch (InflateException e) {
				/* map is already there, just return view as it is */
			}

			return view;
		}

		@Override
		public void onResume() {
			Log.i(TAG, "SaudeFragment onResume");
			super.onResume();
		}

		@Override
		public void onActivityCreated(Bundle savedInstanceState) {
			Log.i(TAG, "SaudeFragment onActivityCreated");
			super.onActivityCreated(savedInstanceState);

		}

		@Override
		public void onAttach(Activity activity) {
			Log.i(TAG, "SaudeFragment onAttach");
			super.onAttach(activity);

		}

	}

	/**
	 * SalvarArena
	 *
	 * @author Guto
	 *
	 */

	public class SalvarArena extends AsyncTask<Void, Void, EventoCadastro> {

		private int arena;
		private Usuario user;

		public SalvarArena(int arena, Usuario user) {
			this.arena = arena;
			this.user = user;
		}

		@Override
		protected EventoCadastro doInBackground(Void... arg0) {

			return new UserDAO().salvaArenaAtiva(arena, user);
		}

		protected void onPostExecute(EventoCadastro evento) {

		}
	}

	/**
	 * SalvarArena
	 *
	 * @author Guto
	 *
	 */

	public class BuscarCalendario extends AsyncTask<Void, Void, List<ItemCalendario>> {

		private Usuario user;

		public BuscarCalendario(Usuario user) {
			this.user = user;
		}

		@Override
		protected List<ItemCalendario> doInBackground(Void... arg0) {

			return new UserDAO().buscarCalendario(user);
		}

		protected void onPostExecute(List<ItemCalendario> retorno) {

			// salvar em preferencias
			new PreferenciasUtil().salvaCalendario(retorno, getApplicationContext());
		}
	}

	@Override
	public void onProviderDisabled(String arg0) {
		// TODO Auto-generated method stub

	}

	@Override
	public void onProviderEnabled(String arg0) {
		// TODO Auto-generated method stub

	}

	@Override
	public void onStatusChanged(String arg0, int arg1, Bundle arg2) {
		// TODO Auto-generated method stub

	}

}
