package br.com.epitrack.healthycup.classes;

public class ItemCalendario {
	private String data;
	private double sentimento;

	public double getSentimento() {
		return sentimento;
	}

	public void setSentimento(double d) {
		this.sentimento = d;
	}

	public String getData() {
		return data;
	}

	public void setData(String data) {
		this.data = data;
	}
}
