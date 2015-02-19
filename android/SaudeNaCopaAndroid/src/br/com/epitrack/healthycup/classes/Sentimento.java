package br.com.epitrack.healthycup.classes;

import java.util.List;

import android.util.Log;
import android.util.TypedValue;
import android.view.View;
import android.view.ViewGroup.LayoutParams;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.ViewFlipper;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.SentimentoActivity;
import br.com.epitrack.healthycup.classes.factory.CategoriaFactory;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

public class Sentimento {

	static final String TAG = "SaudeNaCopa";
	private int id;
	private Usuario user;
	private String latitude;
	private String longitude;
	private int[] campos;
	private int numEstadioDisponivel;

	public Usuario getUser() {
		return user;
	}

	public void setUser(Usuario user) {
		this.user = user;
	}

	public String getLatitude() {
		return latitude;
	}

	public void setLatitude(String latitude) {
		this.latitude = latitude;
	}

	public String getLongitude() {
		return longitude;
	}

	public void setLongitude(String longitude) {
		this.longitude = longitude;
	}

	public int[] getCampos() {
		return campos;
	}

	public void setCampos(int[] campos) {
		this.campos = campos;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public int calcularQuantidadeArena(float porcentagem) {
		if (porcentagem == 0)
			return 1;
		int disponivel = 12;
		float valorMaximo = 80;
		int retorno = Math.min(disponivel, (int) Math.floor(porcentagem / (valorMaximo / disponivel)));
		if (retorno > 12)
			retorno = 12;
		if (retorno == 0) {
			retorno = 1;
		}
		
		return retorno;
	}

	public void montaBotoesEstadio(View rootView, List<Arena> arenas, int arenaAtiva) {
		LinearLayout ll = (LinearLayout) rootView.findViewById(R.id.layBotoesArenas);
		ll.removeAllViews();
		for (int i = 0; i < arenas.size(); i++) {
			View v = new View(rootView.getContext());
			v.setId(i);
			int background = R.drawable.circulo_unselect;
			if (i == arenaAtiva) {
				background = R.drawable.circulo_arena;
			}
			v.setBackgroundResource(background);
			int height = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, rootView.getResources().getDimension(R.dimen.item_circulo),
					rootView.getResources().getDisplayMetrics());
			v.setLayoutParams(new LayoutParams(height, height));
			ll.addView(v);

		}
	}

	public void montaTela(Usuario usuario, View rootView2) {
		// pontos
		TextView txtPontos = (TextView) rootView2.findViewById(R.id.txtPontos);
		txtPontos.setText(String.valueOf(usuario.getPontos())+" "+rootView2.getResources().getString(R.string.pontos));

		// preenche barra de engajamento
		preencheEngajamento(usuario.getEngajamento(), rootView2);

		// manipula categoria e trofeu
		CategoriaFactory cat = new CategoriaFactory(usuario.getCategoria());
		ImageView imgCategoria = (ImageView) rootView2.findViewById(R.id.imgCategoria);
		imgCategoria.setImageResource(cat.getCategoria().getImgCategoria());

		ImageView imgTrofeu = (ImageView) rootView2.findViewById(R.id.imgTrofeuCategoria);
		imgTrofeu.setImageResource(cat.getCategoria().getImgTrofeu());

		
		//TODO atualizar o campo texto informando como está se sentindo
		/*TextView txtTitulo = (TextView)rootView2.findViewById(R.id.txtSentimento);
		String[] txtSentimento = rootView2.getResources().getStringArray(R.array.txt_sentimentos);
		if(this.id>2)
			this.id--;
		txtTitulo.setText(txtSentimento[this.id]);*/

	}

	public void montaBolasArenas(Usuario usuario, View rootView2) {
		// montar bolas de trofeus
		setNumEstadioDisponivel(calcularQuantidadeArena(usuario.getEngajamento()));
		List<Arena> arenas = SentimentoActivity.getArenas(getNumEstadioDisponivel());
		new PreferenciasUtil().salvaNumeroArenas(arenas.size(), rootView2.getContext());
		int arenaAtiva = new PreferenciasUtil().getArenaAtiva(rootView2.getContext());
		montaBotoesEstadio(rootView2, arenas, arenaAtiva);

		setBackgroundsArenas(rootView2, arenas, arenaAtiva);
	}

	public void addArena(View rootView, int numArena) {
		
		List<Arena> arenas = SentimentoActivity.getArenas(numArena);
		
		
		LinearLayout ll = (LinearLayout) rootView.findViewById(R.id.layBotoesArenas);
		View v = new View(rootView.getContext());
		v.setId(numArena-1);
		v.setBackgroundResource(R.drawable.circulo_unselect);
		int height = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, rootView.getResources().getDimension(R.dimen.item_circulo),
				rootView.getResources().getDisplayMetrics());
		v.setLayoutParams(new LayoutParams(height, height));
		ll.addView(v);

		// add arena
		ViewFlipper mFlipper = (ViewFlipper) rootView.findViewById(R.id.viewFlipper);
		RelativeLayout rl = new RelativeLayout(rootView.getContext());
		rl.setBackgroundResource(arenas.get(numArena-1).getImagem());
		rl.setLayoutParams(new FrameLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
		mFlipper.addView(rl);

		new PreferenciasUtil().salvaNumeroArenas(numArena, rootView.getContext());
	}

	public void setBackgroundsArenas(View rootView, List<Arena> arenas, int arenaAtiva) {
		ViewFlipper mFlipper = (ViewFlipper) rootView.findViewById(R.id.viewFlipper);
		mFlipper.removeAllViews();
		for (int i = 0; i < arenas.size(); i++) {
			RelativeLayout rl = new RelativeLayout(rootView.getContext());
			rl.setBackgroundResource(arenas.get(i).getImagem());
			rl.setLayoutParams(new FrameLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
			mFlipper.addView(rl);

		}
		mFlipper.setDisplayedChild(arenaAtiva);
	}

	public void preencheEngajamento(float f, View rootView) {

		if (f == 0)
			return;
		int quadro = (int) Math.abs(f / 8.33);
		if (quadro >= 12) {
			quadro = 12;
		} else {
			quadro++;
		}

		Log.i(TAG, "Engaj: " + String.valueOf(f));
		float half = (float) 4.16;

		int[] imagens = new int[] { R.id.imgEngajamento00, R.id.imgEngajamento01, R.id.imgEngajamento02, R.id.imgEngajamento03,
				R.id.imgEngajamento04, R.id.imgEngajamento05, R.id.imgEngajamento06, R.id.imgEngajamento07, R.id.imgEngajamento08,
				R.id.imgEngajamento09, R.id.imgEngajamento10, R.id.imgEngajamento11 };
		ImageView img;
		for (int i = 0; i < quadro; i++) {
			img = (ImageView) rootView.findViewById(imagens[i]);
			int j = i + 1;

			if (i < quadro - 1) {
				img.setImageResource(R.drawable.quadro_fill);
			} else {
				if (f > 0 && f < half) {
					img.setImageResource(R.drawable.quadro_half_fill);
				}
				if (f > j * 2 * half) {
					img.setImageResource(R.drawable.quadro_fill);
				} else {
					if (f < j * half) {
						img.setImageResource(R.drawable.quadro_half_fill);
					}
					if (f >= j * half && f < j * 2 * half) {
						img.setImageResource(R.drawable.quadro_half_fill);
					}
				}
			}

		}

	}

	public int getNumEstadioDisponivel() {
		return numEstadioDisponivel;
	}

	public void setNumEstadioDisponivel(int numEstadioDisponivel) {
		this.numEstadioDisponivel = numEstadioDisponivel;
	}

}
