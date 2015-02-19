package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class Profissional extends Categoria {

	public Profissional() {
		this.imgCategoria = R.drawable.profissional_categoria;
		this.imgCategoria2 = R.drawable.cat_profissional;
		this.imgTrofeu = R.drawable.profissional_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_pr_f_muitobem, R.drawable.cat_pr_f_bem,
				R.drawable.cat_pr_f_normal, R.drawable.cat_pr_f_mal, R.drawable.cat_pr_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_pr_m_muitobem, R.drawable.cat_pr_m_bem,
				R.drawable.cat_pr_m_normal, R.drawable.cat_pr_m_mal, R.drawable.cat_pr_m_muitomal));
	}
}
