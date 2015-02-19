package br.com.epitrack.healthycup.DAO;

import java.util.List;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;
import br.com.epitrack.healthycup.AdvertenciaActivity;
import br.com.epitrack.healthycup.MudancaNivelActivity;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Arena;
import br.com.epitrack.healthycup.classes.EventoLogin;
import br.com.epitrack.healthycup.classes.ItemCalendario;
import br.com.epitrack.healthycup.classes.Sentimento;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.classes.factory.CategoriaFactory;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

/**
 * Enviar Sentimento
 * 
 * @author Guto
 * 
 */

public class EnviarSentimento extends AsyncTask<Void, Void, EventoLogin> {

	static final String TAG = "SaudeNaCopa";
	private Sentimento sentimento;
	private Context mActivity;
	private View rootView;
	private ViewFlipper lv;
	private int categoriaAnterior;
	private int numEstadioDisponivel;
	TextView textView2;
	TextView txtPontos;

	public EnviarSentimento(Sentimento sentimento, View rootView, FragmentActivity activity, ViewFlipper mViewFlipper) {
		this.sentimento = sentimento;
		this.mActivity = rootView.getContext();
		this.rootView = rootView;
		this.lv = mViewFlipper;
		this.numEstadioDisponivel = new Sentimento().calcularQuantidadeArena(sentimento.getUser().getEngajamento());
	}

	@Override
	protected EventoLogin doInBackground(Void... arg0) {
		categoriaAnterior = sentimento.getUser().getCategoria();
		// verificar se sentimento é mal ou muito mal
		if (sentimento.getId() > 2) {

			Intent intent = new Intent(mActivity, AdvertenciaActivity.class);
			mActivity.startActivity(intent);
		}
		return new UserDAO().enviaSentimento(sentimento);
	}

	protected void onPostExecute(EventoLogin evento) {
		if (evento != null) {
			if (evento.getStatus()) {

				// salvar o user de retorno
				new PreferenciasUtil().salvaUser(evento.getUsuario(), mActivity);

				// verifica se destravou novo estadio
				int numeroEstadioNovo = sentimento.calcularQuantidadeArena(evento.getUsuario().getEngajamento());
				if (numeroEstadioNovo > this.numEstadioDisponivel && categoriaAnterior != evento.getUsuario().getCategoria()) {
					Intent intent = new Intent(mActivity, MudancaNivelActivity.class);
					CategoriaFactory cat = new CategoriaFactory(evento.getUsuario().getCategoria());
					intent.putExtra("imagemCategoria", cat.getCategoria().getImgCategoria());
					intent.putExtra("imagemTrofeu", cat.getCategoria().getImgTrofeu());
					List<Arena> arenas = new ArenaDAO().getArenas();
					Arena arenaNova = arenas.get(numeroEstadioNovo - 1);
					intent.putExtra("background", arenaNova.getImagem());
					intent.putExtra("nomeArena", arenaNova.getNome());
					intent.putExtra("imagemCategoria", cat.getCategoria().getImgCategoria());
					intent.putExtra("imagemTrofeu", cat.getCategoria().getImgTrofeu());

					sentimento.addArena(rootView, sentimento.calcularQuantidadeArena(evento.getUsuario().getEngajamento()));
					// sentimento.montaBolasArenas(evento.getUsuario(),
					// rootView);

					mActivity.startActivity(intent);
				} else {
					if (numeroEstadioNovo > this.numEstadioDisponivel) {
						// abrir tela com novo estádio
						Log.i(TAG, "Novo estádio disponível");
						Intent intent = new Intent(mActivity, MudancaNivelActivity.class);
						List<Arena> arenas = new ArenaDAO().getArenas();
						Arena arenaNova = arenas.get(numeroEstadioNovo - 1);
						intent.putExtra("background", arenaNova.getImagem());
						intent.putExtra("nomeArena", arenaNova.getNome());

						// sentimento.montaBolasArenas(evento.getUsuario(),
						// rootView);
						sentimento.addArena(rootView, sentimento.calcularQuantidadeArena(evento.getUsuario().getEngajamento()));

						mActivity.startActivity(intent);
					}
					// verifica se passou de fase
					if (categoriaAnterior != evento.getUsuario().getCategoria()) {
						// mostrar tela
						Intent intent = new Intent(mActivity, MudancaNivelActivity.class);
						CategoriaFactory cat = new CategoriaFactory(evento.getUsuario().getCategoria());
						intent.putExtra("imagemCategoria", cat.getCategoria().getImgCategoria());
						intent.putExtra("imagemTrofeu", cat.getCategoria().getImgTrofeu());

						mActivity.startActivity(intent);
					}
				}

				// passar os dados para activity
				evento.getUsuario().atualizaImagens();

				// atualizar os dados da tela
				// Sentimento sentimento = new Sentimento();
				sentimento.montaTela(evento.getUsuario(), rootView);

				// TODO atualiza os avatares
				/*
				 * ImageAdapter adapter = (ImageAdapter) lv.getAdapter(); adapter .setImagens(evento.getUsuario().getImagensSentimentos());
				 * adapter.notifyDataSetChanged();
				 */

				// ImageAdapter adapter = new ImageAdapter(activity,
				// evento.getUsuario().getImagensSentimentos());
				// lv.setAdapter(adapter);

				Toast.makeText(mActivity, mActivity.getString(R.string.envio_sentimento_sucesso), Toast.LENGTH_SHORT).show();
			} else {
				// erro no envio de email
				try {
					Toast.makeText(
							mActivity,
							mActivity.getString(R.string.envio_sentimento_erro) + " "
									+ converteHoras(Double.parseDouble(evento.getUsuario().getNumHoraParaInformarSentimento())), Toast.LENGTH_LONG)
							.show();
				} catch (Exception e) {
					// TODO: handle exception
				}
				
			}
		}
		Button btnOk = (Button) rootView.findViewById(R.id.btnInformarSintoma);
		btnOk.setVisibility(View.VISIBLE);
		ProgressBar pbar = (ProgressBar) rootView.findViewById(R.id.progressBar1);
		pbar.setVisibility(View.INVISIBLE);
		
		//atualizar calendario
		
		new BuscarCalendario(sentimento.getUser()).execute();
	}
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
			new PreferenciasUtil().salvaCalendario(retorno, rootView.getContext());
		}
	}

	public String converteHoras(double horas) {
		double h = Math.floor(horas);
		double minutos = (horas - h) * 60;
		//double segundos = (minutos - Math.floor(minutos)) * 60;

		String texto = String.valueOf((int) h) + "h ";
		texto += String.valueOf((int) Math.floor(minutos)) + "m ";
		//texto += String.valueOf((int) Math.floor(segundos)) + "s ";
		return texto;

	}
}
