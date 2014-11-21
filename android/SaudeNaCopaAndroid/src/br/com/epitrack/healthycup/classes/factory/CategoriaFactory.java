package br.com.epitrack.healthycup.classes.factory;

import br.com.epitrack.healthycup.classes.Categoria;
import br.com.epitrack.healthycup.classes.categorias.DenteDeLeite;
import br.com.epitrack.healthycup.classes.categorias.Infantil;
import br.com.epitrack.healthycup.classes.categorias.Junior;
import br.com.epitrack.healthycup.classes.categorias.Juvenil;
import br.com.epitrack.healthycup.classes.categorias.Mirim;
import br.com.epitrack.healthycup.classes.categorias.Profissional;

public class CategoriaFactory {
	private int categoria;
	public CategoriaFactory(int categoria){
		this.categoria = categoria;
	}
	public Categoria getCategoria(){
		Categoria categoriaRetorno=null;
		switch (this.categoria) {
		case 0://dente de leite
			categoriaRetorno = new DenteDeLeite();			
			break;
		case 1://mirim
			categoriaRetorno = new Mirim();			
			break;
		case 2://infantil
			categoriaRetorno = new Infantil();			
			break;
		case 3://juvenil
			categoriaRetorno = new Juvenil();			
			break;
		case 4://junior
			categoriaRetorno = new Junior();			
			break;
		case 5://profissional
			categoriaRetorno = new Profissional();			
			break;

		default:
			break;
		}
		return categoriaRetorno;
	}

}
