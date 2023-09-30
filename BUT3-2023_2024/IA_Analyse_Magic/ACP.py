import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn.decomposition import PCA
from biplot import biplot

data = pd.read_csv('.\\Data\\cards.csv')

"""-------------------- Filter & Delete corrupted data --------------------"""
data.drop_duplicates(subset="name", keep='first', inplace=True)
data.drop(data[data['types'] != 'Creature'].index, inplace = True)

data["manaValue"] = pd.to_numeric(data["manaValue"],downcast="integer")
data['manaValue'] = data['manaValue'].astype(int)

data = data[pd.to_numeric(data["power"], errors='coerce').notnull()]
data["power"] = pd.to_numeric(data["power"],downcast="integer")
data['power'] = data['power'].astype(int)

data = data[pd.to_numeric(data["toughness"], errors='coerce').notnull()]
data["toughness"] = pd.to_numeric(data["toughness"],downcast="integer")
data['toughness'] = data['toughness'].astype(int)

"""-------------------- PCA Analysis --------------------"""
df = data[["toughness","manaValue","power","keywords","printings","rarity"]]
NB_DIM = 6

df.loc[df['rarity']=="common", 'rarity'] = 1
df.loc[df['rarity']=="uncommon", 'rarity'] = 2
df.loc[df['rarity']=="rare", 'rarity'] = 3
df.loc[df['rarity']=="mythic", 'rarity'] = 4
df.loc[df['rarity']=="special", 'rarity'] = 5

df['printings'] = df['printings'].str.split(',').apply(lambda x: len(x))

df["keywords"] = df["keywords"].replace(np.nan, '0')
df["keywords"] = df["keywords"].str.split(',').apply(lambda x: len(x) if(x!=['0']) else 0)

temp = df.sub(df.mean())

# jeu de valeur standardisees
x_scaled = temp.div(df.std()) 

pca = PCA(n_components=NB_DIM)

# Modelisation de l’ACP
pca.fit(x_scaled)

# resultat obtenus pour les individus.
pca_res = pca.fit_transform(x_scaled)
print("""\n\n\n-------------------- pca_res --------------------""")
print(pca_res)

"""-------------------- Valeurs Propres --------------------"""
eig = pd.DataFrame({
    "Dimension":
        ["Dim"+str(x+1) for x in range(NB_DIM)],
        "Valeur propre" : pca.explained_variance_ ,
        "% Valeur propre " :np.round(pca.explained_variance_ratio_ *100),
        "% cum. val. prop.":np.round(np.cumsum(pca.explained_variance_ratio_)*100)
})
print("\n\n\n-------------------- Valeurs Propres --------------------")
print(eig)

y1 = list(pca.explained_variance_ratio_)
x1 = range(len(y1))
plt.bar(x1, y1)
plt.xlabel("ensemble des dimensions")
plt.ylabel("% des valeurs propres")

"""-------------------- Graphique des variables --------------------"""
biplot(
    score=pca_res[:,0:2],
    coeff=np.transpose(pca.components_[0:2,:]),
    cat=y1[0:1],
    density=False,
    coeff_labels = list(df.columns))

"""-------------------- Graphique des individus --------------------"""
pca_df = pd.DataFrame({
    "Dim1":pca_res[:,0],
    "Dim2":pca_res[:,1],
    "rarity":data['rarity']
})

palette=plt.get_cmap("Dark2")
couleurs=dict(zip(pca_df["rarity"].drop_duplicates(),palette(range(5))))
position=dict(zip(couleurs.keys(),range(10)))

pca_df.plot.scatter("Dim1","Dim2",
                    c=[couleurs[p] for p in pca_df["rarity"]])


for cont,coul in couleurs.items():
    plt.scatter(3,position[cont]/3+2.15,c=[coul],s=20,label = cont)

handles, labels = plt.gca().get_legend_handles_labels()
order = [1,0,2,3,4]
plt.legend([handles[idx] for idx in order],[labels[idx] for idx in order],title="Rarity")

plt.xlabel("Dimension1")
plt.ylabel("Dimension2")
plt.suptitle("Premierplanfactoriel(%)")
plt.show()
