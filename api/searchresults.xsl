<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:SearchResults="http://www.zillow.com/static/xsd/SearchResults.xsd">

	<xsl:output method="html" />

	<xsl:template match="SearchResults:searchresults">
		<xsl:variable name="code" select="message/code" />
		<xsl:if test="$code != '0'">
			<center><xsl:value-of select="message/text" /></center>
		</xsl:if>
		<xsl:if test="$code = '0'">
			<xsl:for-each select="response/results/result">
				<div class="row bottom-buffer">
					<div class="col-lg-6">
						<span class="address">
							<xsl:value-of select="address/street" />
							<br />
							<xsl:value-of select="address/city" />,&#160;<xsl:value-of select="address/state" />&#160;<xsl:value-of select="address/zipcode" />
						</span>
						<br />

						Links: 
						<xsl:for-each select="links">
							<xsl:for-each select="*">
								<xsl:variable name="i" select="position()" />
								<xsl:element name="a">
								    <xsl:attribute name="href">
								        <xsl:value-of select="."/>
								    </xsl:attribute>
								    <xsl:value-of select="$i"/>
								</xsl:element>
								&#160;&#160;
							</xsl:for-each>
						</xsl:for-each>
					</div>
					<div class="col-lg-6">
						<center>
							<span class="estimate">$<xsl:value-of select="zestimate/amount"/></span>
							<br />
							<span class="estimate-updated">Updated: <xsl:value-of select="zestimate/last-updated"/></span>
						</center>
					</div>
				</div>
				
			</xsl:for-each>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>