import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn.decomposition import PCA, FactorAnalysis
from sklearn.preprocessing import StandardScaler
import factor_analyzer


def AFC():
    #utiliser le csv
    data = pd.read_csv('.\\Data\\cards.csv',delimiter=",")
    data.drop_duplicates(subset="name", keep='first', inplace=True)

    data = data[["power","manaValue"]]

    data = data.dropna(subset=["power"])
    data = data[pd.to_numeric(data["power"], errors='coerce').notnull()]
    data["power"] = data["power"].astype(float)

    data.drop(data[data["power"] == -1].index, inplace=True)
    data.drop(data[data["power"] > 10].index, inplace=True)

    # seulement 2 variables
    data_crosstab = pd.crosstab(data["manaValue"],data["power"])
    # print(list(data_crosstab))

    # standarnisation des données
    temp = data_crosstab.sub(data_crosstab.mean())
    data_scaled = temp.div(data_crosstab.std())
    

    # vérifiaction p_value
    chi_square_value, p_value = factor_analyzer.calculate_bartlett_sphericity(data_scaled)
    # print(p_value)

    nbMax = min(data_scaled.shape[0]-1,data_scaled.shape[1]-1)
    # print(nbMax)

    fa = factor_analyzer.FactorAnalyzer(n_factors=nbMax, rotation=None)
    fa.fit(data_scaled)
    ev, v = fa.get_eigenvalues()

    plt.scatter(range(1,data_scaled.shape[1]+1),ev)
    plt.plot(range(1,data_scaled.shape[1]+1),ev)
    plt.title("Scree Plot")
    plt.xlabel("Facteurs")
    plt.ylabel("Eigenvalue")
    plt.grid()

    methods=[
        ("FANorotation",FactorAnalysis(4,rotation=None)),
        ("FAVarimax",FactorAnalysis(4,rotation="varimax")),
        ("FAQuartimax",FactorAnalysis(4,rotation="quartimax")),
    ]

    fig ,axes=plt.subplots(ncols=3,figsize=(10,8),sharex=True,sharey=True)
    for ax, (method, fa) in zip(axes,methods):
        fa = fa.fit(data_scaled)
        components = fa.components_
        vmax = np.abs(components).max()
        ax.scatter(components[0,:],components[1,:])
        ax.axhline(0, -1, 1,color="k")
        ax.axvline(0, -1,1,color="k")
        for i,j,z in zip(components[0, :],components[1, :],data_scaled.columns):
            ax.text(i+.02,j+.05,str(z),ha="center")
        for i,j,z in zip(components[0, :],components[1, :],data_scaled.index):
            ax.text(i+.02,j+.02,str(z),ha="center")
        ax.set_title(str(method))
        if ax.get_subplotspec().is_first_col():
            ax.set_ylabel("Factor 1")
        ax.set_xlabel("Factor2")
    
    plt.tight_layout()
    plt.show()

AFC()