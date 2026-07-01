/**
 * lint-staged configuration.
 *
 * Runs phpcs against staged PHP files, excluding third-party (vendor) and
 * test files so pre-commit checks stay focused on the plugin source.
 */
module.exports = {
	'*.php': ( files ) => {
		const relevant = files.filter(
			( file ) =>
				! file.includes( '/vendor/' ) && ! file.includes( '/tests/' )
		);
		if ( relevant.length === 0 ) {
			return [];
		}
		const list = relevant.map( ( file ) => `'${ file }'` ).join( ' ' );
		return [ `composer run phpcs -- ${ list }` ];
	},
};
