package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class Infantil extends Categoria {
	public Infantil() {
		this.imgCategoria = R.drawable.infantil_categoria;
		this.imgCategoria2 = R.drawable.cat_infantil;
		this.imgTrofeu = R.drawable.infantil_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_in_f_muitobem, R.drawable.cat_in_f_bem,
				R.drawable.cat_in_f_normal, R.drawable.cat_in_f_mal, R.drawable.cat_in_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_in_m_muitobem, R.drawable.cat_in_m_bem,
				R.drawable.cat_in_m_normal, R.drawable.cat_in_m_mal, R.drawable.cat_in_m_muitomal));
	}
}
