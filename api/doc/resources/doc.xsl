<?xml version="1.0" encoding="ISO-8859-15"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:ext="http://exslt.org/common" version="1.0">
    <xsl:output method="html"/>
    <xsl:template match="/">
        <html>
            <head>
                <title>Documentation de l'API</title>
            </head>
            <body>
                <xsl:choose>
                    <xsl:when test="response/__called='domains'">
                        <xsl:call-template name='domains'/>
                    </xsl:when>
                    <xsl:when test="response/__called='methods'">
                        <xsl:call-template name='methods'/>
                    </xsl:when>
                    <xsl:otherwise></xsl:otherwise>
                </xsl:choose>
            </body>
        </html>
    </xsl:template>

    <xsl:template name="domains">
        <h1>Liste des domaines disponibles</h1>
        <xsl:for-each select="response/item">
            <h2>
                <a>
                    <xsl:attribute name="href">
                        <xsl:choose>
                            <xsl:when test='../__default'>
                                <xsl:value-of select="concat('methods/',dir,'?rf=xml')"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="concat('../methods/',dir,'?rf=xml')"/>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="name"/>
                </a>
            </h2>
            <div class='description'>
                <xsl:value-of select="description"/>
            </div>
            <div class='copyright'>
                <xsl:value-of select="concat(author,' (c) ',company)"/>
            </div>
        </xsl:for-each>
    </xsl:template>
    
    <xsl:template name='methods'>
        <a href='../domains/?rf=xml'>Domaines</a>
        <h1>Methodes du domaine <xsl:value-of select='response/domain/name'/></h1>
        <div class='description'>
            <xsl:value-of select='response/domain/description'/>
        </div>
        <hr/>
        <xsl:call-template name='showMethods'>
            <xsl:with-param name='type' select="ext:node-set(response/GET)"/>
        </xsl:call-template>
        <xsl:call-template name='showMethods'>
            <xsl:with-param name='type' select="ext:node-set(response/POST)"/>
        </xsl:call-template>
        <xsl:call-template name='showMethods'>
            <xsl:with-param name='type' select="ext:node-set(response/PUT)"/>
        </xsl:call-template>
        <xsl:call-template name='showMethods'>
            <xsl:with-param name='type' select="ext:node-set(response/DELETE)"/>
        </xsl:call-template>
    </xsl:template>
    
    <xsl:template name='showMethods'>
        <xsl:param name='type'/>
        <h1>
            <xsl:value-of select='name($type)'/>
        </h1>
        <xsl:if test='count($type/item)=0'>Aucune methode disponible</xsl:if>
        <xsl:for-each select='$type/item'>
            <div class='method'>
                <div class='title'>
                    <h2>
                        <xsl:value-of select='name'/>
                    </h2>
                </div>
                <div class='desc'><xsl:value-of select='doc/desc'/></div>
                <div class='abstract'>
                    <xsl:value-of select='doc/abstract'/>
                </div>
            </div>
            <div class='call'>
                <xsl:call-template name='getPath'>
                    <xsl:with-param name='doc' select='ext:node-set(doc)'/>
                </xsl:call-template> <xsl:call-template name='getQuery'>
                    <xsl:with-param name='doc' select='ext:node-set(doc)'/>
                </xsl:call-template>
            </div>
            <xsl:if test="count(doc/param/item[type='path'])">
                <div class='call-desc'>
                    <h3>Parametres du chemin</h3>
                    <xsl:for-each select="doc/param/item[type='path']">
                        <xsl:value-of select='datatype'/>
                        <xsl:text> </xsl:text>
                        <b>
                            <xsl:value-of select='name'/>
                        </b>
                        : <xsl:value-of select='desc'/>
                    </xsl:for-each>
                </div>
            </xsl:if>
            <xsl:if test="count(doc/param/item[type='query'])">
                <div class='query-desc'>
                    <h3>Parametres query</h3>
                    <xsl:for-each select="doc/param/item[type='query']">
                        <xsl:value-of select='datatype'/>
                        <xsl:text> </xsl:text>
                        <b>
                            <xsl:value-of select='name'/>
                        </b>
                        : <xsl:value-of select='desc'/>
                    </xsl:for-each>
                </div>
            </xsl:if>
            <xsl:if test='doc/example/call'>
                <h3>Exemple d'appel</h3>
                <div class='call-example'>
                    <xsl:value-of select="doc/example/call"/>
                </div>
            </xsl:if>
            <xsl:if test='doc/example/return'>
                <h3>Exemple de retour</h3>
                <div class='return-example'>
                    <xsl:value-of select="doc/example/return"/>
                </div>
            </xsl:if>
        </xsl:for-each>
    </xsl:template>
    
    <xsl:template name='getPath'>
        <xsl:param name='doc'/>
        <xsl:text>api.php / </xsl:text>
        <xsl:value-of select='/response/domain/dir'/>
        <xsl:text> / </xsl:text>
        <xsl:value-of select='$doc/../name'/>
        <xsl:text> / </xsl:text>
        <xsl:for-each select="$doc/param/item[type='path']">
            <b><xsl:value-of select="name"/>
            <xsl:if test='position()&lt;last()'>
                <xsl:text>/</xsl:text>
            </xsl:if></b>
        </xsl:for-each>
    </xsl:template>
    
    <xsl:template name='getQuery'>
        <xsl:param name='doc'/>
        <xsl:if test="count($doc/param/item[type='query'])"> ? </xsl:if>
        <xsl:for-each select="$doc/param/item[type='query']">
            <b><xsl:value-of select="name"/></b>
            <xsl:if test='position()&lt;last()'>
                <xsl:text> &amp; </xsl:text>
            </xsl:if>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>