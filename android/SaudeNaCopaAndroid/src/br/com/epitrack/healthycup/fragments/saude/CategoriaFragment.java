package br.com.epitrack.healthycup.fragments.saude;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Usuario;
import br.com.epitrack.healthycup.classes.factory.CategoriaFactory;
import br.com.epitrack.healthycup.util.PreferenciasUtil;

import com.google.gson.Gson;

public class CategoriaFragment extends Fragment {

	public CategoriaFragment() {

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
			view = inflater.inflate(R.layout.categorias_fragment, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}
		
		//buscar user para saber qual a cotegoria ele está
		Gson gson = new Gson();
		Usuario user = gson.fromJson(new PreferenciasUtil().getUserCompleto(view.getContext()),Usuario.class);
		
		CategoriaFactory cat = new CategoriaFactory(user.getCategoria());
		//atualiza trofeu e imagem
		ImageView imgCategoria = (ImageView)view.findViewById(R.id.imgCategoria);
		ImageView imgTrofeu = (ImageView)view.findViewById(R.id.imgTrofeu);
		imgCategoria.setImageResource(cat.getCategoria().getImgCategoria2());
		imgTrofeu.setImageResource(cat.getCategoria().getImgTrofeu());
		return view;
	}
}