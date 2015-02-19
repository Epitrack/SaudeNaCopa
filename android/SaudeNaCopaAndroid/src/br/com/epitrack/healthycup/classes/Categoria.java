package br.com.epitrack.healthycup.classes;

import java.util.ArrayList;

public class Categoria {
	protected int imgCategoria;
	protected int imgCategoria2;
	public int getImgCategoria2() {
		return imgCategoria2;
	}
	public void setImgCategoria2(int imgCategoria2) {
		this.imgCategoria2 = imgCategoria2;
	}
	protected int imgTrofeu;
	protected ArrayList<Integer> imagensSentimentosFeminino;
	protected ArrayList<Integer> imagensSentimentosMasculino;
	
	public int getImgCategoria() {
		return imgCategoria;
	}
	public void setImgCategoria(int imgCategoria) {
		this.imgCategoria = imgCategoria;
	}
	public int getImgTrofeu() {
		return imgTrofeu;
	}
	public void setImgTrofeu(int imgTrofeu) {
		this.imgTrofeu = imgTrofeu;
	}
	public ArrayList<Integer> getImagensSentimentosFeminino() {
		return imagensSentimentosFeminino;
	}
	public void setImagensSentimentosFeminino(ArrayList<Integer> imagensSentimentosFeminino) {
		this.imagensSentimentosFeminino = imagensSentimentosFeminino;
	}
	public ArrayList<Integer> getImagensSentimentosMasculino() {
		return imagensSentimentosMasculino;
	}
	public void setImagensSentimentosMasculino(ArrayList<Integer> imagensSentimentosMasculino) {
		this.imagensSentimentosMasculino = imagensSentimentosMasculino;
	}

}
